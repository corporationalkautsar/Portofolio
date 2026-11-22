<?php

namespace App\Http\Middleware;

use App\Models\Profile;
use App\Models\Setting;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'locale' => fn () => app()->getLocale(),
            'locales' => fn () => config('app.supported_locales'),
            'translations' => fn () => Lang::get('ui'),
            'auth' => [
                'user' => fn () => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'is_admin' => $request->user()->is_admin,
                ] : null,
            ],
            'flash' => fn () => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            // Lightweight globals used by the public layout and footer.
            'site' => fn () => [
                'profile' => Profile::query()->first(),
                'socialLinks' => SocialLink::query()->orderBy('platform')->get(),
                'settings' => Setting::query()->pluck('value', 'key'),
            ],
        ];
    }
}
