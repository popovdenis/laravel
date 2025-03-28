<?php
namespace App\Blog\Middleware;

use Closure;

class UserCanManageBlogPosts
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403, 'You are not logged in');
        }

        if (!method_exists(auth()->user(), 'canManageBlogPosts') || !auth()->user()->canManageBlogPosts()) {
            abort(403, 'Your account is not authorised to edit blog posts');
        }

        return $next($request);
    }
}
