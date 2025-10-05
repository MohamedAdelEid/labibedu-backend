<?php

namespace App\Presentation\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('Accept-Language', 'en');

        // Support only 'en' and 'ar'
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}