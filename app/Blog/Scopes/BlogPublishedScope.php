<?php

namespace App\Blog\Scopes;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class BlogPublishedScope implements Scope
{
    /**
     * If user is logged in and canManageBlogPosts() == true, then don't add any scope
     * But for everyone else then it should only show PUBLISHED posts with a POSTED_AT < NOW()
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Illuminate\Database\Eloquent\Model   $model
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (!auth()->user() || !auth()->user()->canManageBlogPosts()) {
            // user is a guest, or if logged in they can't manage blog posts
            $builder->where("is_published", true);
            $builder->where("posted_at", "<=", Carbon::now());
        }
    }
}
