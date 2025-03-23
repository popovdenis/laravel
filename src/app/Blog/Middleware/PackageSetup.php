<?php
namespace App\Blog\Middleware;

use Closure;
use App\Blog\Models\Configuration;

class PackageSetup
{
    public function handle($request, Closure $next)
    {
        $initial_setup = Configuration::get('INITIAL_SETUP');
        if (!$initial_setup) {
            return redirect(route('blog.admin.setup'));
        }

        return $next($request);
    }
}
