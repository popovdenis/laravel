<?php

namespace App\Blog\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Blog\Models\Post;

/**
 * Class BlogPostAdded
 *
 * @package Blog\Events
 */
class BlogPostAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var  Post */
    public $blogPost;

    /**
     * BlogPostAdded constructor.
     *
     * @param Post $blogPost
     */
    public function __construct(Post $blogPost)
    {
        $this->blogPost = $blogPost;
    }

}
