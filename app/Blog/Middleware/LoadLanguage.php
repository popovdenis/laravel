<?php

namespace App\Blog\Middleware;

use Closure;
use App\Blog\Models\Configuration;
use App\Blog\Models\Language;

class LoadLanguage
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
