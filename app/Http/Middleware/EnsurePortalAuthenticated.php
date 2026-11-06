<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePortalAuthenticated
{
    /**
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $guard = $request->session()->get('portal_guard');

        if (! in_array($guard, ['web', 'clients'], true) || ! Auth::guard($guard)->check()) {
            Auth::guard('web')->logout();
            Auth::guard('clients')->logout();
            $request->session()->forget('portal_guard');

            return redirect()->guest(route('login'));
        }

        $account = Auth::guard($guard)->user();
        $isBlocked = $guard === 'web'
            ? $account->blokir === 'Y'
                || ($account->tanggalkadaluarsa !== null
                    && Carbon::parse($account->tanggalkadaluarsa)->lt(Carbon::today()))
            : ($account->blocked === 'Y'
                || ($account->expired_date !== null && $account->expired_date->lt(Carbon::today())));

        if ($isBlocked) {
            Auth::guard($guard)->logout();
            $request->session()->forget('portal_guard');
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->guest(route('login'));
        }

        return $next($request);
    }
}
