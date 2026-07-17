<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', $request->cookie('locale', config('app.locale')));

        if (in_array($locale, ['en', 'ar'])) {
            app()->setLocale($locale);
        }

        $response = $next($request);

        if (in_array($locale, ['en', 'ar']) && $response instanceof \Illuminate\Http\Response) {
            $response->cookie('locale', $locale, 60 * 24 * 365); // 1 year
        }

        return $response;
    }
}
