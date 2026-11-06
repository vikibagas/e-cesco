<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $guard = $request->session()->get('portal_guard');
        $account = in_array($guard, ['web', 'clients'], true)
            ? Auth::guard($guard)->user()
            : null;

        return [
            ...parent::share($request),
            'locale' => App::currentLocale(),
            'auth' => [
                'user' => $account === null ? null : [
                    'name' => $guard === 'web'
                        ? ($account->namalengkap ?: $account->name)
                        : ($account->fullname ?: $account->username),
                    'username' => $account->username,
                    'role' => $guard === 'web' ? $account->level : 'customer',
                ],
            ],
            'flash' => [
                'success' => fn (): ?string => $request->session()->get('success'),
                'error' => fn (): ?string => $request->session()->get('error'),
            ],
            'navigation' => Lang::get('navigation'),
            'site' => [
                'homeUrl' => route('home'),
                'servicesUrl' => route('home').'#layanan',
                'aboutUrl' => route('about'),
                'networkUrl' => route('home').'#jaringan',
                'contactUrl' => route('home').'#kontak',
                'portalUrl' => route('login'),
                'loginUrl' => route('login'),
                'localeUrls' => [
                    'id' => url()->current().'?lang=id',
                    'en' => url()->current().'?lang=en',
                ],
            ],
        ];
    }
}
