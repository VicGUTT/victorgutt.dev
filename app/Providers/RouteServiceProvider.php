<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Domain\Analytics\Middlewares\TrackPageview;
use Domain\Locale\Middlewares\EnsureLocaleIsUpToDate;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

final class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function (): void {
            $this->patterns();

            $this->redirectRoutes();
            $this->webRoutes();
            $this->devRoutes();
        });
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', static function (Request $request) {
            /** @var User|null */
            $user = $request->user();

            return Limit::perMinute(60)->by((string) ($user?->id ?: $request->ip()));
        });
    }

    /* Routes setup
    ------------------------------------------------*/

    private function patterns(): void
    {
        // /**
        //  * @see vendor/laravel/framework/src/Illuminate/Support/Facades/Route.php
        //  * @see vendor/laravel/framework/src/Illuminate/Routing/Router.php
        //  * @see vendor/laravel/framework/src/Illuminate/Routing/RouteRegistrar.php
        //  * @see vendor/laravel/framework/src/Illuminate/Routing/CreatesRegularExpressionRouteConstraints.php
        //  */
        // Route::pattern('slug', '[a-z0-9\-]+');
        // Route::whereNumber('id');

        // Route::whereAlphaNumeric('key');
        Route::whereAlphaNumeric('token');
        // Route::whereAlphaNumeric('hash');

        Route::pattern('path', '.*'); // '(.*(?:%2F:)?.*)'
    }

    private function redirectRoutes(): void
    {
        Route::middleware('web')
            ->name('redirect:')
            ->group(base_path('routes/redirects.php'));
    }

    private function webRoutes(): void
    {
        Route::middleware(['web', EnsureLocaleIsUpToDate::class, TrackPageview::class])
            ->prefix('{locale?}')
            ->name('web:')
            ->group(base_path('routes/web.php'));
    }

    private function devRoutes(): void
    {
        if (!app()->isLocal()) {
            return;
        }

        Route::middleware('web')
            ->prefix('dev')
            ->name('dev:')
            ->group(base_path('routes/dev.php'));
    }
}
