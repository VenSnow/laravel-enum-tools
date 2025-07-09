<?php

namespace EnumTools\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetEnumLocale
{
    public function handle($request, Closure $next)
    {
        if (!config('enum_tools.enable_locale_middleware', true)) {
            return $next($request);
        }

        $locale = $request->get('lang')
            ?? $request->header('X-Locale')
            ?? $request->header('Accept-Language');

        $supported = config('enum_tools.supported_locales', ['en']);

        if ($locale && in_array($locale, $supported)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
