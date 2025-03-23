<?php

namespace App\Blog\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Blog\Models\Comment;
use App\Blog\Models\Post;

/**
 * Class CommentAdded
 *
 * @package Blog\Events
 */
class CommentAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var  Post */
    public $blogPost;
    /** @var  Comment */
    public $newComment;

    /**
     * CommentAdded constructor.
     *
     * @param Post    $blogPost
     * @param Comment $newComment
     */
    public function __construct(Post $blogPost, Comment $newComment)
    {
        $this->blogPost = $blogPost;
        $this->newComment = $newComment;
    }

}
