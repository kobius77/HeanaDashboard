<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // 1. Check User Preference (DB)
        if (auth()->check()) {
            $userLocale = auth()->user()->locale;
            if ($userLocale && $userLocale !== 'auto') {
                $locale = $userLocale;
            }
        }
        // 2. Check Session (Guest)
        elseif (session()->has('locale')) {
            $sessionLocale = session()->get('locale');
            if ($sessionLocale !== 'auto') {
                $locale = $sessionLocale;
            }
        }

        // 3. Auto / Browser Detection
        if (! $locale) {
            $supported = ['de', 'en'];
            foreach ($request->getLanguages() as $lang) {
                // Determine the base language (e.g., 'en-US' -> 'en')
                $baseLang = substr($lang, 0, 2);
                if (in_array($baseLang, $supported)) {
                    $locale = $baseLang;
                    break;
                }
            }
        }

        // 4. Apply Locale
        if ($locale) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
