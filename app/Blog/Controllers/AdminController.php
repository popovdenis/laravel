<?php
namespace App\Blog\Controllers;

use App\Blog\Events\BlogPostAdded;
use App\Blog\Events\BlogPostWillBeDeleted;
use App\Blog\FulltextSearch\Search;
use App\Blog\Helpers;
use App\Blog\Interfaces\BaseRequestInterface;
use App\Blog\Middleware\LoadLanguage;
use App\Blog\Middleware\PackageSetup;
use App\Blog\Middleware\UserCanManageBlogPosts;
use App\Blog\Models\Category;
use App\Blog\Models\CategoryTranslation;
use App\Blog\Models\Language;
use App\Blog\Models\Post;
use App\Blog\Models\PostTranslation;
use App\Blog\Models\UploadedPhoto;
use App\Blog\Requests\CreateBlogPostRequest;
use App\Blog\Requests\CreatePostToggleRequest;
use App\Blog\Requests\DeleteBlogPostRequest;
use App\Blog\Requests\UpdateBlogPostRequest;
use App\Blog\Traits\UploadFileTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Base\Http\Controllers\Controller;

/**
 * Class AdminController
 * @package App\Blog\Controllers
 */
class AdminController extends Controller
{
    use UploadFileTrait;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(UserCanManageBlogPosts::class);
        $this->middleware(LoadLanguage::class);
        $this->middleware(PackageSetup::class);

        if (!is_array(config("blog"))) {
            throw new \RuntimeException('The config/blog.php does not exist. Publish the vendor files for the Blog package by running the php artisan publish:vendor command');
        }
    }

    /**
     * View all posts
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $posts = PostTranslation::orderBy("post_id", "desc")->paginate(10);

        return view("blog_admin.index", [
            'post_translations'=>$posts,
            'language_id' => 1
        ]);
    }

    /**
     * Show form for creating new post
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create_post(Request $request)
    {
        $languageId = $request->get('language_id');
        $languageList = Language::where('active', true)->get();
        $ts = CategoryTranslation::where("lang_id", $languageId)->limit(1000)->get();

        $newPost = new Post();
        $newPost->is_published = true;

        return view("blog_admin::posts.add_post", [
            'cat_ts' => $ts,
            'language_list' => $languageList,
            'selected_lang' => $languageId,
            'post' => $newPost,
            'post_translation' => new \App\Blog\Models\PostTranslation(),
            'post_id' => -1
        ]);
    }

    /**
     * Save a new post - this method is called whenever add post button is clicked
     *
     * @param CreateBlogPostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function store_post(CreateBlogPostRequest $request)
    {
        $translation = PostTranslation::where(
            [
                ['post_id','=',$request['post_id']],
                ['lang_id', '=', 1]
            ]
        )->first();

        if (!$translation){
            $translation = new PostTranslation();
        }

        if ($request['post_id'] == -1 || $request['post_id'] == null){
            $newBlogPost = new Post();
            $translation = new PostTranslation();

            $newBlogPost->posted_at = Carbon::now();
        }else{
            $newBlogPost = Post::findOrFail($request['post_id']);
        }

        $postExists = $this->checkSamePostExists($request['slug'] , 1, $request['post_id']);
        if ($postExists){
            Helpers::flash_message("Post already exists - try to change the slug for this language");
        } else {
            $newBlogPost->is_published = $request['is_published'];
            $newBlogPost->user_id = auth()->user()->getAuthIdentifier();
            $newBlogPost->save();

            $translation->title = $request['title'];
            $translation->subtitle = $request['subtitle'];
            $translation->short_description = $request['short_description'];
            $translation->post_body = $request['post_body'];
            $translation->seo_title = $request['seo_title'];
            $translation->meta_desc = $request['meta_desc'];
            $translation->slug = $request['slug'];
            $translation->use_view_file = $request['use_view_file'];

            $translation->lang_id = 1;
            $translation->post_id = $newBlogPost->id;

            $this->processUploadedImages($request, $translation);
            $translation->save();

            $newBlogPost->categories()->sync($request->categories());
            Helpers::flash_message("Added post");
            event(new BlogPostAdded($newBlogPost));
        }

        return redirect( route('blog.admin.index') );
    }

    /**
     *  This method is called whenever a language is selected
     */
    public function store_post_toggle(CreatePostToggleRequest $request){
        $translation = PostTranslation::where([
            ['post_id','=',$request['post_id']],
            ['lang_id', '=', 1]
        ])->first();

        if (!$translation){
            $translation = new PostTranslation();
        }

        if ($request['post_id'] == -1 || $request['post_id'] == null){
            $newBlogPost = new Post();
            $newBlogPost->is_published = true;
            $newBlogPost->posted_at = Carbon::now();
        }else{
            $newBlogPost = Post::findOrFail($request['post_id']);
        }

        if ($request['slug']){
            $postExists = $this->checkSamePostExists($request['slug'] , 1, $newBlogPost->id);
            if ($postExists){
                Helpers::flash_message("Post already exists - try to change the slug for this language");
            }else{
                $newBlogPost->is_published = $request['is_published'];
                $newBlogPost->user_id = auth()->user()->getAuthIdentifier();
                $newBlogPost->save();

                $translation->title = $request['title'];
                $translation->subtitle = $request['subtitle'];
                $translation->short_description = $request['short_description'];
                $translation->post_body = $request['post_body'];
                $translation->seo_title = $request['seo_title'];
                $translation->meta_desc = $request['meta_desc'];
                $translation->slug = $request['slug'];
                $translation->use_view_file = $request['use_view_file'];

                $translation->lang_id = 1;
                $translation->post_id = $newBlogPost->id;

                $this->processUploadedImages($request, $translation);
                $translation->save();

                $newBlogPost->categories()->sync($request->categories());

                event(new BlogPostAdded($newBlogPost));
            }
        }

        //todo: generate event

        $languageId = $request->get('language_id');
        $languageList = Language::where('active', true)->get();
        $ts = CategoryTranslation::where("lang_id", $languageId)->limit(1000)->get();

        $translation = PostTranslation::where([
            ['post_id','=',$request['post_id']],
            ['lang_id', '=', $request['selected_lang']]
        ])->first();
        if (!$translation){
            $translation = new PostTranslation();
        }

        return view("blog_admin::posts.add_post", [
            'cat_ts' => $ts,
            'language_list' => $languageList,
            'selected_lang' => $request['selected_lang'],
            'post_translation' => $translation,
            'post' => $newBlogPost,
            'post_id' => $newBlogPost->id
        ]);
    }

    /**
     * Show form to edit post
     *
     * @param $blogPostId
     * @return mixed
     */
    public function edit_post( $blogPostId , Request $request)
    {
        $languageId = $request->get('language_id');
        $postTranslation = PostTranslation::where([
            ['lang_id', '=', $languageId],
            ['post_id', '=', $blogPostId]
        ])->first();

        $post = Post::findOrFail($blogPostId);
        $languageList = Language::where('active', true)->get();
        $ts = CategoryTranslation::where("lang_id", $languageId)->limit(1000)->get();

        return view("blog_admin::posts.edit_post", [
            'cat_ts' => $ts,
            'language_list' => $languageList,
            'selected_lang' => $languageId,
            'selected_locale' => Language::where('id', $languageId)->first()->locale,
            'post' => $post,
            'post_translation' => $postTranslation
        ]);
    }

    /**
     * Show form to edit post
     *
     * @param $blogPostId
     * @return mixed
     */
    public function edit_post_toggle( $blogPostId , Request $request)
    {
        $postTranslation = PostTranslation::where([
            ['lang_id', '=', $request['selected_lang']],
            ['post_id', '=', $blogPostId]
        ])->first();
        if (!$postTranslation){
            $postTranslation = new PostTranslation();
        }

        $post = Post::findOrFail($blogPostId);
        $languageList = Language::where('active',true)->get();
        $ts = CategoryTranslation::where("lang_id", $request['selected_lang'])->limit(1000)->get();

        return view("blog_admin::posts.edit_post", [
            'cat_ts' => $ts,
            'language_list' => $languageList,
            'selected_lang' => $request['selected_lang'],
            'selected_locale' => Language::where('id', $request['selected_lang'])->first()->locale,
            'post' => $post,
            'post_translation' => $postTranslation
        ]);
    }

    /**
     * Save changes to a post
     *
     * @param UpdateBlogPostRequest $request
     * @param $blogPostId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function update_post(UpdateBlogPostRequest $request, $blogPostId)
    {
        $newBlogPost = Post::findOrFail($blogPostId);
        $translation = PostTranslation::where([
            ['post_id','=', $newBlogPost->id],
            ['lang_id', '=', 1]
        ])->first();

        if (!$translation){
            $translation = new PostTranslation();
            $newBlogPost->posted_at = Carbon::now();
        }

        $postExists = $this->checkSamePostExists($request['slug'] , 1, $blogPostId);
        if ($postExists){
            Helpers::flash_message("Post already exists - try to change the slug for this language");
        } else {
            $newBlogPost->is_published = $request['is_published'];
            $newBlogPost->user_id = auth()->user()->getAuthIdentifier();
            $newBlogPost->save();

            $translation->title = $request['title'];
            $translation->subtitle = $request['subtitle'];
            $translation->short_description = $request['short_description'];
            $translation->post_body = $request['post_body'];
            $translation->seo_title = $request['seo_title'];
            $translation->meta_desc = $request['meta_desc'];
            $translation->slug = $request['slug'];
            $translation->use_view_file = $request['use_view_file'];

            $translation->lang_id = 1;
            $translation->post_id = $newBlogPost->id;

            $this->processUploadedImages($request, $translation);
            $translation->save();

            $newBlogPost->categories()->sync($request->categories());
            Helpers::flash_message("Post Updated");
            event(new BlogPostAdded($newBlogPost));
        }

        return redirect( route('blog.admin.index') );
    }

    public function remove_photo($postSlug)
    {
        $post = PostTranslation::where([
            ["slug", '=', $postSlug],
            ['lang_id', '=', 1]
        ])->firstOrFail();

        $path = public_path('/' . config("blog.blog_upload_dir"));
        if (!$this->checked_blog_image_dir_is_writable) {
            if (!is_writable($path)) {
                throw new \RuntimeException("Image destination path is not writable ($path)");
            }
        }

        $destinationPath = $this->image_destination_path();

        if (file_exists($destinationPath.'/'.$post->image_large)) {
            unlink($destinationPath.'/'.$post->image_large);
        }

        if (file_exists($destinationPath.'/'.$post->image_medium)) {
            unlink($destinationPath.'/'.$post->image_medium);
        }

        if (file_exists($destinationPath.'/'.$post->image_thumbnail)) {
            unlink($destinationPath.'/'.$post->image_thumbnail);
        }

        $post->image_large = null;
        $post->image_medium = null;
        $post->image_thumbnail = null;
        $post->save();

        Helpers::flash_message("Photo removed");

        return redirect($post->editUrl());
    }

    /**
     * Delete a post
     *
     * @param DeleteBlogPostRequest $request
     * @param $blogPostId
     * @return mixed
     */
    public function destroy_post(DeleteBlogPostRequest $request, $blogPostId)
    {
        $post = Post::findOrFail($blogPostId);
        $post->delete();
        event(new BlogPostWillBeDeleted($post));

        Helpers::flash_message("Post successfully deleted!");

        return redirect( route('blog.admin.index') );
    }

    /**
     * Process any uploaded images (for featured image)
     *
     * @param BaseRequestInterface $request
     * @param $newBlogPost
     * @throws \Exception
     * @todo - next full release, tidy this up!
     */
    protected function processUploadedImages(BaseRequestInterface $request, PostTranslation $newBlogPost)
    {
        if (!config("blog.image_upload_enabled")) {
            return;
        }

        $this->increaseMemoryLimit();

        // to save in db later
        $uploadedImageDetails = [];
        foreach ((array)config('blog.image_sizes') as $size => $imageSizeDetails) {
            if ($imageSizeDetails['enabled'] && $photo = $request->get_image_file($size)) {
                $uploadedImage = $this->UploadAndResize($newBlogPost, $newBlogPost->slug, $imageSizeDetails, $photo);
                $newBlogPost->$size = $uploadedImage['filename'];
                $uploadedImageDetails[$size] = $uploadedImage;
            }
        }

        // store the image upload.
        // todo: link this to the blog_post row.
        if (count(array_filter($uploadedImageDetails))>0) {
            UploadedPhoto::create([
                'source' => "BlogFeaturedImage",
                'uploaded_images' => $uploadedImageDetails,
            ]);
        }
    }

    protected function checkSamePostExists($slug, $langId, $postId){
        $slg = PostTranslation::where([
            ['slug','=', $slug],
            ['lang_id', '=', $langId],
            ['post_id', '<>', $postId]
        ])->first();

        return (bool) $slg;
    }

    /**
     * Show the search results for $_GET['s']
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function searchBlog(Request $request)
    {
        if (!config("blog.search.search_enabled")) {
            throw new \Exception("Search is disabled");
        }
        $query = $request->get("s");
        $search = new Search();
        $searchResults = $search->run($query);

        \View::share("title", "Search results for " . e($query));

        $rootList = Category::roots()->get();
        Category::loadSiblingsWithList($rootList);

        $languageId = $request->get('language_id');

        return view("blog_admin.index", [
            'search' => true,
            'post_translations'=> $searchResults,
            'language_id' => $languageId
        ]);
    }
}
