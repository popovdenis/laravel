<?php
namespace App\Blog\Requests;

use Illuminate\Validation\Rule;
use App\Blog\Requests\Traits\HasCategoriesTrait;
use App\Blog\Requests\Traits\HasImageUploadTrait;

class CreateBlogPostRequest extends BaseBlogPostRequest
{
    use HasCategoriesTrait;
    use HasImageUploadTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $return = $this->baseBlogPostRules();
        return $return;
    }

}
