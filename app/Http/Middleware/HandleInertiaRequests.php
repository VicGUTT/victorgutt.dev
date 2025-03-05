<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Inertia\Middleware;
use Illuminate\Http\Request;
use Domain\Analytics\Pirsch\Pirsch;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): string
    {
        // return parent::version($request);

        /**
         * @see https://github.com/inertiajs/inertia-laravel/pull/653
         */
        return (string) hash_file('xxh128', public_path('build/manifest.json'));
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(Request $request): array
    {
        return array_filter([
            ...parent::share($request),
            'app' => fn () => $this->shareApp($request),
            // 'auth' => $this->whenAuthenticated(fn () => $this->shareAuth($request)),
            'meta' => fn () => $this->shareMeta($request),
            // 'redirect' => fn () => $this->shareRedirect($request),
            // 'authorizations' => $this->whenAuthenticated(fn () => $this->shareAuthorizations($request)),
        ]);
    }

    private function shareApp(Request $request): array
    {
        $config = [
            'name' => config('app.name'),
            'url' => config('app.url'),
            'locale' => app()->getLocale(),
            'fallback_locale' => app()->getFallbackLocale(),
            'supported_locales' => config('app.supported_locales'),
        ];

        if ((config('app.debug') === true) && !app()->isProduction()) {
            $config['debug'] = true;
        }

        if (!app()->isProduction()) {
            $config['env'] = config('app.env');
        }

        return array_filter([
            ...$config,
            //
        ]);
    }

    // private function shareAuth(Request $request): array
    // {
    //     return array_filter([
    //         'authenticated' => Auth::check() ? true : null,
    //         'user' => UserData::from($request->user()),
    //         'organization' => OrganizationData::from($request->user()->organization),
    //     ]);
    // }

    private function shareMeta(Request $request): array
    {
        $visit = [];

        if (Pirsch::resolve()->enabled()) {
            $visit = array_filter([
                'enabled' => true,
                'referrer' => $request->inertia() ? null : $request->header('Referer'),
            ]);
        }

        return array_filter([
            'visit' => $visit,
        ]);
    }

    // private function shareRedirect(Request $request): array
    // {
    //     return array_filter([
    //         'data' => $request->session()->get('redirect.data'),
    //     ]);
    // }

    // private function shareAuthorizations(Request $request): array
    // {
    //     return array_filter([
    //         //
    //     ]);
    // }

    // private function whenAuthenticated(mixed $value): mixed
    // {
    //     if (!Auth::check()) {
    //         return null;
    //     }

    //     return $value;
    // }
}
