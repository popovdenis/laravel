<?php

namespace App\Blog\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddNewCommentRequest extends FormRequest
{
    public function authorize()
    {
        if (config("blog.comments.type_of_comments_to_show") === 'built_in') {
            // anyone is allowed to submit a comment, to return true always.
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // basic rules
        $return = [
            'comment' => ['required', 'string', 'min:3', 'max:1000'],
            'author_name' => ['string', 'min:1', 'max:50'],
            'author_email' => ['string', 'nullable', 'min:1', 'max:254', 'email'],
            'author_website' => ['string', 'nullable', 'min:' . strlen("http://a.b"), 'max:175', 'active_url',],
        ];

        // do we need author name?
        if (auth()->user() && config("blog.comments.save_user_id_if_logged_in", true)) {
            // is logged in, so we don't need an author name (it won't get used)
            $return['author_name'][] = 'nullable';
        } else {
            // is a guest - so we require this
            $return['author_name'][] = 'required';
        }

        // in case you need to implement something custom, you can use this...
        if (config("blog.comments.rules") && is_callable(config("blog.comments.rules"))) {
            /** @var callable $func */
            $func = config('blog.comments.rules');
            $return = $func($return);
        }

        if (config("blog.comments.require_author_email")) {
            $return['author_email'][] = 'required';
        }

        return $return;
    }
}
