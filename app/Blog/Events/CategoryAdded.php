<?php

namespace App\Blog\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Blog\Models\Category;
use App\Blog\Models\CategoryTranslation;

/**
 * Class CategoryAdded
 *
 * @package Blog\Events
 */
class CategoryAdded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var  Category */
    public $category;
    public $categoryTranslation;

    /**
     * CategoryAdded constructor.
     *
     * @param Category $category
     */
    public function __construct(Category $category, CategoryTranslation $categoryTranslation)
    {
        $this->category = $category;
        $this->categoryTranslation = $categoryTranslation;
    }
}
