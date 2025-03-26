<?php

namespace App\Blog\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Blog\Models\Category;

/**
 * Class CategoryWillBeDeleted
 *
 * @package Blog\Events
 */
class CategoryWillBeDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var  Category */
    public $blogCategory;

    /**
     * CategoryWillBeDeleted constructor.
     *
     * @param Category $blogCategory
     */
    public function __construct(Category $blogCategory)
    {
        $this->blogCategory = $blogCategory;
    }

}
