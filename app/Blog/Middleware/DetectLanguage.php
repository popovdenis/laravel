<?php
namespace App\Blog\Middleware;

use App\Blog\Models\Configuration;
use Closure;
use App\Blog\Models\Language;

class DetectLanguage
{
    public function handle($request, Closure $next)
    {
        $locale = $request->route('locale');
        $routeWithoutLocale = false;

        if (!$request->route('locale')){
            $routeWithoutLocale = true;
            $locale = Configuration::get('DEFAULT_LANGUAGE_LOCALE');
        }

        $lang = Language::where('locale', $locale)
            ->where('active', true)
            ->first();

        if (!$lang){
            return abort(404);
        }

        $request->attributes->add([
            'lang_id' => $lang->id,
            'locale' => $lang->locale,
            'routeWithoutLocale' => $routeWithoutLocale
        ]);

        return $next($request);
    }
}
