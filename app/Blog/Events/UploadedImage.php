<?php

namespace App\Blog\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Blog\Models\Post;
use App\Blog\Models\PostTranslation;

/**
 * Class UploadedImage
 *
 * @package Blog\Events
 */
class UploadedImage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var  Post|null */
    public $blogPost;
    /**
     * @var
     */
    public $image;

    public $source;
    public $image_filename;

    /**
     * UploadedImage constructor.
     *
     * @param string                                $image_filename
     * @param                                       $image
     * @param \App\Blog\Models\PostTranslation|null $blogPost
     * @param string                                $source
     */
    public function __construct(string $image_filename, $image, PostTranslation $blogPost = null, $source = 'other')
    {
        $this->image_filename = $image_filename;
        $this->blogPost = $blogPost;
        $this->image = $image;
        $this->source = $source;
    }
}
