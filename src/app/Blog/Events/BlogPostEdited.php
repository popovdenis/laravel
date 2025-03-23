<?php

namespace App\Blog\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Blog\Models\Post;

/**
 * Class BlogPostEdited
 *
 * @package Blog\Events
 */
class BlogPostEdited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var  Post */
    public $blogPost;

    /**
     * BlogPostEdited constructor.
     *
     * @param Post $blogPost
     */
    public function __construct(Post $blogPost)
    {
        $this->blogPost = $blogPost;
    }

}
