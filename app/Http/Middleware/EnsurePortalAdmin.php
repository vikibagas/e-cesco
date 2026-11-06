<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->session()->get('portal_guard') === 'web'
                && Auth::guard('web')->user()?->level === 'admin',
            Response::HTTP_FORBIDDEN,
        );

        return $next($request);
    }
}
