<?php

namespace App\Blog\Controllers;

use App\Blog\Middleware\UserCanManageBlogPosts;
use App\Blog\Models\UploadedPhoto;
use App\Blog\Requests\UploadImageRequest;
use App\Blog\Traits\UploadFileTrait;
use Modules\Base\Http\Controllers\Controller;

/**
 * Class AdminController
 *
 * @package App\Blog\Controllers
 */
class ImageUploadController extends Controller
{
    use UploadFileTrait;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(UserCanManageBlogPosts::class);

        if (!is_array(config("blog"))) {
            throw new \RuntimeException('The config/blog.php does not exist. Publish the vendor files for the Blog package by running the php artisan publish:vendor command');
        }


        if (!config("blog.image_upload_enabled")) {
            throw new \RuntimeException("The blog.php config option has not enabled image uploading");
        }
    }

    /**
     * Show the main listing of uploaded images
     *
     * @return mixed
     */
    public function index()
    {
        return view("blog_admin::imageupload.index", [
            'uploaded_photos' => UploadedPhoto::orderBy("id", "desc")->paginate(10)
        ]);
    }

    /**
     * show the form for uploading a new image
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view("blog_admin::imageupload.create", []);
    }

    /**
     * Save a new uploaded image
     *
     * @param UploadImageRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function store(UploadImageRequest $request)
    {
        $processed_images = $this->processUploadedImages($request);

        return view("blog_admin::imageupload.uploaded", ['images' => $processed_images]);
    }

    /**
     * Process any uploaded images (for featured image)
     *
     * @param UploadImageRequest $request
     *
     * @return array returns an array of details about each file resized.
     * @throws \Exception
     */
    protected function processUploadedImages(UploadImageRequest $request)
    {
        $this->increaseMemoryLimit();
        $photo = $request->file('upload');

        $uploadedImageDetails = [];
        $sizes_to_upload = $request->get("sizes_to_upload");

        if (isset($sizes_to_upload['blog_full_size']) && $sizes_to_upload['blog_full_size'] === 'true') {
            $uploadedImageDetails['blog_full_size'] = $this->UploadAndResize(null, $request->get("image_title"), 'fullsize', $photo);
        }

        foreach ((array) config('blog.image_sizes') as $size => $imageSizeDetails) {
            if (!isset($sizes_to_upload[$size]) || !$sizes_to_upload[$size] || !$imageSizeDetails['enabled']) {
                continue;
            }
            $uploadedImageDetails[$size] = $this->UploadAndResize(null, $request->get("image_title"), $imageSizeDetails, $photo);
        }

        UploadedPhoto::create([
            'image_title' => $request->get("image_title"),
            'source' => "ImageUpload",
            'uploader_id' => auth()->user()->getAuthIdentifier(),
            'uploaded_images' => $uploadedImageDetails,
        ]);

        return $uploadedImageDetails;
    }
}
