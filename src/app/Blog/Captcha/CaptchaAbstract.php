<?php

namespace App\Blog\Captcha;

use Illuminate\Http\Request;
use App\Blog\Interfaces\CaptchaInterface;
use App\Blog\Models\Post;
use App\Blog\Models\PostTranslation;

abstract class CaptchaAbstract implements CaptchaInterface
{
    /**
     * executed when viewing single post
     *
     * @param Request                 $request
     * @param PostTranslation         $blogPost
     *
     * @return void
     */
    public function runCaptchaBeforeShowingPosts(Request $request, PostTranslation $blogPost)
    {
        // no code here to run! Maybe in your subclass you can make use of this?
        /*

        But you could put something like this -
        $some_question = ...
        $correct_captcha = ...
        \View::share("correct_captcha",$some_question); // << reference this in the view file.
        \Session::put("correct_captcha",$correct_captcha);


        then in the validation rules you can check if the submitted value matched the above value. You will have to implement this.

        */
    }

    /**
     * executed when posting new comment
     *
     * @param Request      $request
     * @param Post         $blogPost
     *
     * @return void
     */
    public function runCaptchaBeforeAddingComment(Request $request, Post $blogPost)
    {
        // no code here to run! Maybe in your subclass you can make use of this?
    }

}
