<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = 'en';

        if ($request->user()) {
            $locale = $request->user()->preferred_language ?? 'en';
        } elseif ($request->session()->has('locale')) {
            $locale = $request->session()->get('locale');
        }

        app()->setLocale($locale);

        view()->share('dir', $locale === 'ar' ? 'rtl' : 'ltr');
        view()->share('lang', $locale);
        view()->share('currentLocale', $locale);
        view()->share('isRTL', $locale === 'ar');

        return $next($request);
    }
}
