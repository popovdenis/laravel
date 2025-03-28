<?php

namespace App\Blog\Models;

use App\Blog\FulltextSearch\Indexable;
use Illuminate\Database\Eloquent\Model;
use App\Blog\Interfaces\SearchResultInterface;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PostTranslation extends Model implements SearchResultInterface
{
    use Indexable;

    protected $indexContentColumns = ['post_body', 'short_description', 'meta_desc',];
    protected $indexTitleColumns = ['title', 'subtitle', 'seo_title',];

    public $fillable = [
        'title',
        'subtitle',
        'short_description',
        'post_body',
        'seo_title',
        'meta_desc',
        'slug',
        'use_view_file',
        'lang_id',
        'post_id',
        'image_large',
    ];

    /**
     * Get the user that owns the phone.
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * The associated Language
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function language()
    {
        return $this->hasOne(Language::class, "lang_id");
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    : array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function search_result_page_url()
    {
        return $this->url();
    }

    public function search_result_page_title()
    {
        return $this->title;
    }

    /**
     * If $this->user_view_file is not empty, then it'll return the dot syntax location of the blade file it should
     * look for
     *
     * @return string
     * @throws \Exception
     */
    public function fullViewFilePath()
    {
        if (!$this->use_view_file) {
            throw new \RuntimeException("use_view_file was empty, so cannot use " . __METHOD__);
        }
        return "custom_blog_posts." . $this->use_view_file;
    }

    public function getImage(string $size = null)
    : string
    {
        $original = $this->image_large;

        $config = config("blog.image_sizes");

        if (empty($config) || !isset($config[$size])) {
            return $original;
        }

        $imageConfig = $config[$size];

        if (!$imageConfig || !isset($imageConfig['w'], $imageConfig['h'])) {
            return Storage::url("blog_images/{$original}");
        }

        if (Storage::disk('public')->exists($original)) {
            $ext = pathinfo($original, PATHINFO_EXTENSION);
            $base = pathinfo($original, PATHINFO_FILENAME);
            $resizedFilename = sprintf('%s-%s-%s.%s', $this->post_id, $base, $size, $ext);
            $resizedPath = dirname($original) . '/' . $resizedFilename;

            if (!Storage::disk('public')->exists($resizedPath)) {
                $manager = new ImageManager(new Driver());
                $image = $manager->read(Storage::disk('public')->path($original));
                $image->scaleDown($imageConfig['w'], $imageConfig['h']);
                $image->save(Storage::disk('public')->path($resizedPath), quality: config('blog.image_quality', 80));
            }

            return Storage::url($resizedPath);
        }

        return Storage::url($original);
    }

    public function postBodyOutput()
    {
        if (config("blog.use_custom_view_files") && $this->use_view_file) {
            // using custom view files is enabled, and this post has a use_view_file set, so render it:
            $return = view("blog::partials.use_view_file", ['post' => $this])->render();
        } else {
            // just use the plain ->post_body
            $return = $this->post_body;
        }


        if (!config("blog.echo_html")) {
            // if this is not true, then we should escape the output
            if (config("blog.strip_html")) {
                $return = strip_tags($return);
            }

            $return = e($return);
            if (config("blog.auto_nl2br")) {
                $return = nl2br($return);
            }
        }

        return $return;
    }

    /**
     *
     * If $this->seo_title was set, return that.
     * Otherwise just return $this->title
     *
     * Basically return $this->seo_title ?? $this->title;
     *
     * @return string
     */
    public function genSeoTitle()
    {
        if ($this->seo_title) {
            return $this->seo_title;
        }
        return $this->title;
    }

    /**
     * Returns the public facing URL to view this blog post
     *
     * @return string
     */
    public function url(): string
    {
        return route('blog.single', ['blogPostSlug' => $this->slug]);
    }

    /**
     * Return the URL for editing the post (used for admin users)
     *
     * @return string
     */
    public function editUrl()
    {
        return route("blog.admin.edit_post", $this->post_id);
    }
}
