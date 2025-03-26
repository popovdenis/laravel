<?php

namespace App\Blog\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Blog\Interfaces\BaseRequestInterface;

/**
 * Class BaseRequest
 * @package Blog\Requests
 */
abstract class BaseRequest extends FormRequest implements BaseRequestInterface
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check() && \Auth::user()->canManageBlogPosts();
    }
}
