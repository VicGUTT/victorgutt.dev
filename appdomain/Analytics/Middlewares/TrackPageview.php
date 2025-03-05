<?php

declare(strict_types=1);

namespace Domain\Analytics\Middlewares;

use Closure;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Domain\Analytics\Pirsch\Pirsch;
use Illuminate\Http\RedirectResponse;

/**
 * @see https://github.com/pirsch-analytics/laravel-pirsch/blob/74659dc66cb6229ed3ffd7798cee5ab06d23d406/src/Http/Middleware/TrackPageview.php
 */
final class TrackPageview
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse|JsonResponse)  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if (!Pirsch::resolve()->enabled()) {
            return $response;
        }

        if ($response instanceof RedirectResponse) {
            return $response;
        }

        // if ($request->hasHeader('X-Livewire')) {
        //     return $response;
        // }

        // if (str_starts_with($request->route()->uri, 'telescope/')) {
        //     return $response;
        // }

        // if ($request->route()->named('abc')) {
        //     return $response;
        // }

        if (!$request->isMethodSafe()) {
            return $response;
        }

        if (!$request->inertia()) {
            return $response;
        }

        app()->terminating(static function (): void {
            try {
                Pirsch::resolve()->trackHit();
            } catch (Throwable $th) {
                report($th);
            }
        });

        return $response;
    }
}
