<?php

namespace App\Blog\Controllers;

use App\Http\Controllers\Controller;
use App\Blog\Events\CommentAdded;
use App\Blog\Models\Comment;
use App\Blog\Models\PostTranslation;
use App\Blog\Requests\AddNewCommentRequest;

/**
 * Class CommentWriterController
 *
 * @package App\Blog\Controllers
 */
class CommentWriterController extends Controller
{
    /**
     * Let a guest (or logged in user) submit a new comment for a blog post
     *
     * @param AddNewCommentRequest $request
     * @param                      $blogPostSlug
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function addNewComment(AddNewCommentRequest $request, $blogPostSlug)
    {
        if (config("blog.comments.type_of_comments_to_show", "built_in") !== 'built_in') {
            throw new \RuntimeException("Built in comments are disabled");
        }

        $post_translation = PostTranslation::where("slug", $blogPostSlug)
            ->with('post')
            ->firstOrFail();
        $blogPost = $post_translation->post;

        $newComment = $this->createNewComment($request, $blogPost);

        return view("blog::saved_comment", [
//            'captcha' => $captcha,
            'blog_post' => $post_translation,
            'new_comment' => $newComment
        ]);
    }

    /**
     * @param AddNewCommentRequest $request
     * @param                      $blogPost
     *
     * @return Comment
     */
    protected function createNewComment(AddNewCommentRequest $request, $blogPost)
    {
        $newComment = new Comment($request->all());

        if (config("blog.comments.save_ip_address")) {
            $newComment->ip = $request->ip();
        }
        if (config("blog.comments.ask_for_author_website")) {
            $newComment->author_website = $request->get('author_website');
        }
        if (config("blog.comments.ask_for_author_email")) {
            $newComment->author_email = $request->get('author_email');
        }
        if (config("blog.comments.save_user_id_if_logged_in", true) && auth()->user()) {
            $newComment->user_id = auth()->user()->getAuthIdentifier();
        }

        $newComment->approved = config("blog.comments.auto_approve_comments", true) ? true : false;

        $blogPost->comments()->save($newComment);

        event(new CommentAdded($blogPost, $newComment));

        return $newComment;
    }
}
