<?php

namespace App\Blog\Controllers;

use App\Http\Controllers\Controller;
use Auth;
use App\Blog\Captcha\CaptchaAbstract;
use App\Blog\Captcha\UsesCaptcha;
use App\Blog\Events\CommentAdded;
use App\Blog\Middleware\LoadLanguage;
use App\Blog\Middleware\UserCanManageBlogPosts;
use App\Blog\Models\Comment;
use App\Blog\Models\Post;
use App\Blog\Models\PostTranslation;
use App\Blog\Requests\AddNewCommentRequest;

/**
 * Class CommentWriterController
 * @package App\Blog\Controllers
 */
class CommentWriterController extends Controller
{
//    use UsesCaptcha;

//    public function __construct()
//    {
////        $this->middleware(UserCanManageBlogPosts::class);
////        $this->middleware(LoadLanguage::class);
//
//    }

    /**
     * Let a guest (or logged in user) submit a new comment for a blog post
     *
     * @param AddNewCommentRequest $request
     * @param $blog_post_slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function addNewComment(AddNewCommentRequest $request, $blog_post_slug)
    {
        if (config("blog.comments.type_of_comments_to_show", "built_in") !== 'built_in') {
            throw new \RuntimeException("Built in comments are disabled");
        }

        $post_translation = PostTranslation::where("slug", $blog_post_slug)
            ->with('post')
            ->firstOrFail();
        $blog_post = $post_translation->post;

//        /** @var CaptchaAbstract $captcha */
//        $captcha = $this->getCaptchaObject();
//        if ($captcha) {
//            $captcha->runCaptchaBeforeAddingComment($request, $blog_post);
//        }

        $new_comment = $this->createNewComment($request, $blog_post);

        return view("blog::saved_comment", [
//            'captcha' => $captcha,
            'blog_post' => $post_translation,
            'new_comment' => $new_comment
        ]);
    }

    /**
     * @param AddNewCommentRequest $request
     * @param $blog_post
     * @return Comment
     */
    protected function createNewComment(AddNewCommentRequest $request, $blog_post)
    {
        $new_comment = new Comment($request->all());

        if (config("blog.comments.save_ip_address")) {
            $new_comment->ip = $request->ip();
        }
        if (config("blog.comments.ask_for_author_website")) {
            $new_comment->author_website = $request->get('author_website');
        }
        if (config("blog.comments.ask_for_author_email")) {
            $new_comment->author_email = $request->get('author_email');
        }
        if (config("blog.comments.save_user_id_if_logged_in", true) && auth()->user()) {
            $new_comment->user_id = auth()->user()->getAuthIdentifier();
        }

        $new_comment->approved = config("blog.comments.auto_approve_comments", true) ? true : false;

        $blog_post->comments()->save($new_comment);

        event(new CommentAdded($blog_post, $new_comment));

        return $new_comment;
    }
}
