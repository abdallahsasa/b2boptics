<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locales = ['en', 'ar', 'tr', 'zh', 'ru', 'de', 'es', 'fr', 'it'];
        $locale = $request->segment(1);

        // If the first segment is a valid locale
        if (in_array($locale, $locales)) {
            App::setLocale($locale);
            session(['locale' => $locale]);
            URL::defaults(['locale' => $locale]);
        } else {
            // If it's not a locale, and not a system path (like admin, livewire, etc.)
            if (!in_array($locale, ['admin', 'livewire', 'lang', 'api', 'auth', 'storage'])) {
                $sessionLocale = session('locale', config('app.locale'));
                return redirect('/' . $sessionLocale . '/' . $request->path());
            }
        }

        return $next($request);
    }
}
