<?php

namespace App\Blog\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Blog\Scopes\BlogCommentApprovedAndDefaultOrderScope;

class Comment extends Model
{
    public $casts = [
        'approved' => 'boolean',
    ];

    public $fillable = [
        'comment',
        'author_name',
    ];

    /**
     * The associated Post
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class,"post_id");
    }

    /**
     * Comment author user (if set)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config("blog.user_model"), 'user_id');
    }

    /**
     * Return author string (either from the User (via ->user_id), or the submitted author_name value
     *
     * @return string
     */
    public function author()
    {
        if ($this->user_id) {
            $field = config("blog.comments.user_field_for_author_name","name");
            return optional($this->user)->$field;
        }

        return $this->author_name;
    }
}
