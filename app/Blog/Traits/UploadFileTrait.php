<?php
namespace App\Blog\Traits;

use Illuminate\Http\UploadedFile;
use App\Blog\Events\UploadedImage;
use App\Blog\Models\Post;
use App\Blog\Models\PostTranslation;

trait UploadFileTrait
{
    /** How many tries before we throw an Exception error */
    static int $attemptsFindFilename = 100;

    /**
     * If false, we check if the blog_images/ dir is writable, when uploading images
     *
     * @var bool
     */
    protected bool $imageDirWritable = false;

    /**
     * Small method to increase memory limit.
     * This can be defined in the config file. If blog.memory_limit is false/null then it won't do anything.
     * This is needed though because if you upload a large image it'll not work
     */
    protected function increaseMemoryLimit()
    {
        // increase memory - change this setting in config file
        if (config("blog.memory_limit")) {
            @ini_set('memory_limit', config("blog.memory_limit"));
        }
    }

    /**
     * Get a filename (that doesn't exist) on the filesystem.
     *
     * Todo: support multiple filesystem locations.
     *
     * @param string       $suggestedTitle
     * @param              $imageSizeDetails - either an array (with w/h attributes) or a string
     * @param UploadedFile $photo
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getImageFilename(string $suggestedTitle, $imageSizeDetails, UploadedFile $photo)
    {
        $base = $this->generate_base_filename($suggestedTitle);
        // $wh will be something like "-1200x300"
        $wh = $this->getWhForFilename($imageSizeDetails);
        $ext = '.' . $photo->getClientOriginalExtension();

        for ($i = 1; $i <= self::$attemptsFindFilename; $i++) {
            $suffix = $i > 1 ? '-' . str_random(5) : '';
            $attempt = str_slug($base . $suffix . $wh) . $ext;
            if (!File::exists($this->image_destination_path() . "/" . $attempt)) {
                // filename doesn't exist, let's use it!
                return $attempt;
            }

        }
        throw new \RuntimeException("Unable to find a free filename after $i attempts - aborting now.");
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    protected function image_destination_path()
    {
        $path = public_path('/' . config("blog.blog_upload_dir"));
        $this->check_image_destination_path_is_writable($path);
        return $path;
    }

    /**
     * @param Post $new_blog_post
     * @param      $suggestedTitle    - used to help generate the filename
     * @param      $imageSizeDetails - either an array (with 'w' and 'h') or a string (and it'll be uploaded at full
     *                                 size, no size reduction, but will use this string to generate the filename)
     * @param      $photo
     *
     * @return array
     * @throws \Exception
     */
    protected function UploadAndResize(PostTranslation $new_blog_post = null, $suggestedTitle, $imageSizeDetails, $photo)
    {
        // get the filename/filepath
        $image_filename = $this->getImageFilename($suggestedTitle, $imageSizeDetails, $photo);
        $destinationPath = $this->image_destination_path();

        // make image
        $resizedImage = \Image::make($photo->getRealPath());

        if (is_array($imageSizeDetails)) {
            // resize to these dimensions:
            $w = $imageSizeDetails['w'];
            $h = $imageSizeDetails['h'];

            if (isset($imageSizeDetails['crop']) && $imageSizeDetails['crop']) {
                $resizedImage = $resizedImage->fit($w, $h);
            } else {
                $resizedImage = $resizedImage->resize($w, $h, function ($constraint)
                {
                    $constraint->aspectRatio();
                });
            }
        } elseif ($imageSizeDetails === 'fullsize') {
            // nothing to do here - no resizing needed.
            // We just need to set $w/$h with the original w/h values
            $w = $resizedImage->width();
            $h = $resizedImage->height();
        } else {
            throw new \Exception("Invalid image_size_details value");
        }

        // save image
        $resizedImage->save($destinationPath . '/' . $image_filename, config("blog.image_quality", 80));

        // fireevent
        event(new UploadedImage($image_filename, $resizedImage, $new_blog_post, __METHOD__));

        // return the filename and w/h details
        return [
            'filename' => $image_filename,
            'w' => $w,
            'h' => $h,
        ];

    }

    /**
     * Get the width and height as a string, with x between them
     * (123x456).
     *
     * It will always be prepended with '-'
     *
     * Example return value: -123x456
     *
     * $imageSizeDetails should either be an array with two items ([$width, $height]),
     * or a string.
     *
     * If an array is given:
     * getWhForFilename([123,456]) it will return "-123x456"
     *
     * If a string is given:
     * getWhForFilename("some string") it will return -some-string". (max len: 30)
     *
     * @param array|string $imageSizeDetails
     *
     * @return string
     * @throws \RuntimeException
     */
    protected function getWhForFilename($imageSizeDetails)
    {
        if (is_array($imageSizeDetails)) {
            return '-' . $imageSizeDetails['w'] . 'x' . $imageSizeDetails['h'];
        } elseif (is_string($imageSizeDetails)) {
            return "-" . str_slug(substr($imageSizeDetails, 0, 30));
        }

        // was not a string or array, so error
        throw new \RuntimeException("Invalid image_size_details: must be an array with w and h, or a string");
    }

    /**
     * Check if the image destination directory is writable.
     * Throw an exception if it was not writable
     *
     * @param $path
     *
     * @throws \RuntimeException
     */
    protected function check_image_destination_path_is_writable($path)
    {
        if (!$this->imageDirWritable) {
            if (!is_writable($path)) {
                throw new \RuntimeException("Image destination path is not writable ($path)");
            }
            $this->imageDirWritable = true;
        }
    }

    /**
     * @param string $suggestedTitle
     *
     * @return string
     */
    protected function generate_base_filename(string $suggestedTitle)
    {
        $base = substr($suggestedTitle, 0, 100);
        if (!$base) {
            // if we have an empty string then we should use a random one:
            $base = 'image-' . str_random(5);
            return $base;
        }
        return $base;
    }
}
