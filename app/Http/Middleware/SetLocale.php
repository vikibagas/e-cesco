<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang');

        if (in_array($locale, ['id', 'en'], true)) {
            $request->session()->put('locale', $locale);
        } else {
            $locale = $request->session()->get('locale', 'id');
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
