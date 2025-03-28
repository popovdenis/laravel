<?php

namespace App\Blog\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog\Events\CommentApproved;
use App\Blog\Events\CommentWillBeDeleted;
use App\Blog\Helpers;
use App\Blog\Middleware\LoadLanguage;
use App\Blog\Middleware\UserCanManageBlogPosts;
use App\Blog\Models\Comment;

/**
 * Class CommentsAdminController
 *
 * @package App\Blog\Controllers
 */
class CommentsAdminController extends Controller
{
    /**
     * CommentsAdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(UserCanManageBlogPosts::class);

    }

    /**
     * Show all comments (and show buttons with approve/delete)
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $comments = Comment::withoutGlobalScopes()->orderBy("created_at", "desc")->with("post");

        if ($request->get("waiting_for_approval")) {
            $comments->where("approved", false);
        }

        $comments = $comments->paginate(100);

        return view("blog_admin::comments.index")->withComments($comments);
    }


    /**
     * Approve a comment
     *
     * @param $blogCommentId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($blogCommentId)
    {
        $comment = Comment::withoutGlobalScopes()->findOrFail($blogCommentId);
        $comment->approved = true;
        $comment->save();

        Helpers::flash_message("Approved!");
        event(new CommentApproved($comment));

        return back();

    }

    /**
     * Delete a submitted comment
     *
     * @param $blogCommentId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($blogCommentId)
    {
        $comment = Comment::withoutGlobalScopes()->findOrFail($blogCommentId);
        event(new CommentWillBeDeleted($comment));

        $comment->delete();

        Helpers::flash_message("Deleted!");
        return back();
    }


}
