<?php namespace App\Blog\Requests\Traits;

use App\Blog\Models\Category;

/**
 * Class HasCategoriesTrait
 *
 * @package Blog\Requests\Traits
 */
trait HasCategoriesTrait
{
    /**
     * If $_GET['category'] slugs were submitted, then it should return an array of the IDs
     *
     * @return array
     */
    public function categories()
    {
        if (!$this->get("category") || !is_array($this->get("category"))) {
            return [];
        }

        $vals = Category::whereIn("id", array_keys($this->get("category")))->select("id")->limit(1000)->get();
        $vals = array_values($vals->pluck("id")->toArray());

        return $vals;
    }
}
