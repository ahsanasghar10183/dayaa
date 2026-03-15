<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * Locale priority:
     * 1. Session-stored locale (user has explicitly switched language)
     * 2. Authenticated user's preferred locale (if stored on user model)
     * 3. Default app locale (de)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = ['en', 'de'];

        // Get locale from session
        $locale = session('locale');

        // Validate it's a supported locale
        if (!$locale || !in_array($locale, $supportedLocales)) {
            $locale = config('app.locale', 'de');
        }

        App::setLocale($locale);

        // Also set the locale in the session if not already set
        if (!session()->has('locale')) {
            session(['locale' => $locale]);
        }

        return $next($request);
    }
}
