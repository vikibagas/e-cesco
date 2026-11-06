<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Auth\UserClient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function create(): Response|RedirectResponse
    {
        if (Auth::guard('web')->check() || Auth::guard('clients')->check()) {
            return redirect()->route('e-cesco.dashboard');
        }

        return Inertia::render('Auth/Login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['sometimes', 'boolean'],
        ]);

        $staff = User::query()->where('username', $credentials['username'])->first();
        $client = UserClient::query()->where('username', $credentials['username'])->first();
        $hasAmbiguousUsername = $staff !== null && $client !== null;
        $account = $staff ?? $client;
        $storedPassword = $account?->getAuthPassword();
        $isLegacyMd5 = $storedPassword !== null
            && password_get_info($storedPassword)['algoName'] === 'unknown'
            && preg_match('/\A[a-f0-9]{32}\z/i', $storedPassword) === 1;
        $passwordIsValid = $storedPassword !== null && ($isLegacyMd5
            ? hash_equals(strtolower($storedPassword), md5($credentials['password']))
            : Hash::check($credentials['password'], $storedPassword));
        $isBlocked = $staff !== null
            ? $staff->blokir === 'Y'
            : ($client !== null && $client->blocked === 'Y');
        $isExpiredClient = $client !== null
            && $client->expired_date !== null
            && $client->expired_date->lt(Carbon::today());
        $isExpiredStaff = $staff !== null
            && $staff->tanggalkadaluarsa !== null
            && Carbon::parse($staff->tanggalkadaluarsa)->lt(Carbon::today());

        if ($hasAmbiguousUsername || ! $passwordIsValid || $isBlocked || $isExpiredClient || $isExpiredStaff) {
            throw ValidationException::withMessages([
                'username' => app()->isLocale('id')
                    ? 'Username atau kata sandi tidak valid.'
                    : 'The username or password is incorrect.',
            ]);
        }

        $guard = $staff !== null ? 'web' : 'clients';
        if ($isLegacyMd5 || Hash::needsRehash($storedPassword)) {
            $account->password = Hash::make($credentials['password']);
            $account->save();
        }

        Auth::guard($guard)->login($account, $request->boolean('remember'));
        $request->session()->regenerate();
        $request->session()->put('portal_guard', $guard);

        return redirect()->route('e-cesco.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        Auth::guard('clients')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
