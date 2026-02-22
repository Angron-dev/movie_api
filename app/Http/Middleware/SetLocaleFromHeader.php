<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleFromHeader
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language');

        if ($locale) {
            // Pobiera pierwszy język z listy np. pl-PL,pl;q=0.9
            $locale = explode(',', $locale)[0];
            $locale = explode(';', $locale)[0];

            // Ustaw locale tylko jeśli jest wspierany
            if (in_array($locale, config('app.supported_locales'))) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}
