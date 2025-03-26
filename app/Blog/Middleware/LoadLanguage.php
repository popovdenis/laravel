<?php

namespace App\Blog\Middleware;

use Closure;
use App\Blog\Models\Configuration;
use App\Blog\Models\Language;

class LoadLanguage
{
    public function handle($request, Closure $next)
    {
        $default_locale = Configuration::get('DEFAULT_LANGUAGE_LOCALE');
        $lang = Language::where('locale', $default_locale)
            ->first();

        $request->attributes->add([
            'locale' => $lang->locale,
            'language_id' => $lang->id
        ]);

        return $next($request);
    }
}
