<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang')
            ?? $request->session()->get('locale')
            ?? config('app.locale');

        if (in_array($locale, ['fr', 'ar', 'en'])) {
            App::setLocale($locale);
            $request->session()->put('locale', $locale);
        }

        view()->share('currentLocale', App::getLocale());

        return $next($request);
    }
}
