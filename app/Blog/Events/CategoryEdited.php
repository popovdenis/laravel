<?php

namespace App\Blog\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Blog\Models\Category;

/**
 * Class CategoryEdited
 *
 * @package Blog\Events
 */
class CategoryEdited
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var  Category */
    public $blogCategory;

    /**
     * CategoryEdited constructor.
     *
     * @param Category $blogCategory
     */
    public function __construct(Category $blogCategory)
    {
        $this->blogCategory = $blogCategory;
    }
}
