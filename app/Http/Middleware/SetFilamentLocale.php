<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetFilamentLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locales = ['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'];

        // 1. Check if locale is explicitly passed in query string (e.g., ?lang=ar or ?locale=ar)
        $requestedLocale = $request->query('lang') ?? $request->query('locale');
        if ($requestedLocale && in_array($requestedLocale, $locales)) {
            session(['locale' => $requestedLocale]);
        }

        // 2. Read from session
        $locale = session('locale');

        // 3. Fallback to authenticated user's factory default language if available
        if (!$locale && auth()->check() && !empty(auth()->user()->factory?->language)) {
            $factoryLang = auth()->user()->factory->language;
            if (in_array($factoryLang, $locales)) {
                $locale = $factoryLang;
                session(['locale' => $locale]);
            }
        }

        // 4. Default to app.locale if not set
        $locale = in_array($locale, $locales) ? $locale : config('app.locale', 'en');

        App::setLocale($locale);

        return $next($request);
    }
}
