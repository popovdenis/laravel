<?php

namespace App\Blog\Requests;

use Illuminate\Validation\Rule;
use App\Blog\Models\Category;

class UpdateBlogCategoryRequest extends BaseBlogCategoryRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $return = $this->baseCategoryRules();
        return $return;

    }
}
