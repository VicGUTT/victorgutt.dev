<?php

declare(strict_types=1);

use Tests\FeatureTestCase;
use Domain\Session\Session;
use Illuminate\Support\Uri;
use Domain\Locale\Enums\LocaleEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Domain\Locale\Middlewares\EnsureLocaleIsUpToDate;
use Domain\Locale\Support\LocaleAwareRouteFallbackHandler;

dataset('route_names', [
    'test:home',
    'test:post.index',
    'test:post.show',
]);

beforeEach(function (): void {
    $middlewares = ['web', EnsureLocaleIsUpToDate::class];
    $prefix = '{locale?}';

    $handler = function (): string {
        return Route::currentRouteName()
            . '-'
            . app()->getLocale()
            . ':'
            . Session::resolve()->getLocale()?->value;
    };

    Route::fallback((new LocaleAwareRouteFallbackHandler())(...));

    /**
     * Note: Route grouping did not work for some reason.
     */
    Route::middleware($middlewares)->prefix($prefix)->name('test:home')->get('/', $handler);
    Route::middleware($middlewares)->prefix($prefix)->name('test:post.index')->get('/posts', $handler);
    Route::middleware($middlewares)->prefix($prefix)->name('test:post.show')->get('/posts/123', $handler);
});

describe('Updating locale from route param', function (): void {
    it('accepts a supported locale', function (string $routeName): void {
        /** @var FeatureTestCase $this */
        $response = $this->get(route($routeName, ['locale' => LocaleEnum::FR]));

        $response
            ->assertStatus(200)
            ->assertContent("{$routeName}-" . LocaleEnum::FR->value . ':' . LocaleEnum::FR->value);
    })->with('route_names');

    it('accepts a supported locale no matter the casing', function (string $routeName): void {
        /** @var FeatureTestCase $this */
        $response = $this->get(route($routeName, ['locale' => mb_strtoupper(LocaleEnum::FR->value)]));

        $response
            ->assertStatus(200)
            ->assertContent("{$routeName}-" . LocaleEnum::FR->value . ':' . LocaleEnum::FR->value);

        $response = $this->get(route($routeName, ['locale' => mb_strtolower(LocaleEnum::FR->value)]));

        $response
            ->assertStatus(200)
            ->assertContent("{$routeName}-" . LocaleEnum::FR->value . ':' . LocaleEnum::FR->value);
    })->with('route_names');

    it('redirects to the default locale when an unsupported locale has been provided', function (string $routeName): void {
        /** @var FeatureTestCase $this */
        $url = route($routeName, ['locale' => 'nope']);

        $response = $this->get($url);

        $response->assertRedirect(
            str($url)->replace('nope', LocaleEnum::default()->value . '/nope')->value(),
        );
    })->with('route_names');

    it('redirects to the default locale when no locale has been provided', function (string $routeName): void {
        /** @var FeatureTestCase $this */
        $uri = Uri::of(route($routeName));

        $response = $this->get($uri->value());

        $response->assertRedirect(
            mb_trim($uri->withPath(LocaleEnum::default()->value . "/{$uri->path()}")->value(), '/'),
        );
    })->with('route_names');
});

describe('Updating locale from storage', function (): void {
    it('can use the in session locale when no locale specified', function (string $routeName): void {
        /** @var FeatureTestCase $this */
        $response = $this->get(route($routeName));

        $response->assertRedirect(route($routeName, ['locale' => LocaleEnum::default()]));

        Session::resolve()->setLocale(LocaleEnum::FR);

        $response = $this->get(route($routeName));

        $response->assertRedirect(route($routeName, ['locale' => LocaleEnum::FR]));

        Session::resolve()->setLocale(LocaleEnum::EN);

        $response = $this->get(route($routeName));

        $response->assertRedirect(route($routeName, ['locale' => LocaleEnum::EN]));
    })->with('route_names');
});

describe('Updating locale from preferred language', function (): void {
    it('accepts a supported locale', function (string $routeName): void {
        /** @var FeatureTestCase $this */
        $response = $this->get(route($routeName), ['Accept-Language' => 'fr,en-US,en;q=0.5']);

        $response->assertRedirect(route($routeName, ['locale' => LocaleEnum::FR]));

        session()->flush();

        $response = $this->get(route($routeName), ['Accept-Language' => 'en,fr-FR,fr;q=0.5']);

        $response->assertRedirect(route($routeName, ['locale' => LocaleEnum::EN]));
    })->with('route_names');

    it('accepts a supported locale no matter the casing', function (string $routeName): void {
        /** @var FeatureTestCase $this */
        $response = $this->get(route($routeName), ['Accept-Language' => 'FR,en-US,en;q=0.5']);

        $response->assertRedirect(route($routeName, ['locale' => LocaleEnum::FR]));

        session()->flush();

        $response = $this->get(route($routeName), ['Accept-Language' => 'EN,fr-FR,fr;q=0.5']);

        $response->assertRedirect(route($routeName, ['locale' => LocaleEnum::EN]));
    })->with('route_names');
});

describe('Misc.', function (): void {
    it('falls back when provided with an supported preferred language', function (string $routeName): void {
        /** @var FeatureTestCase $this */
        $response = $this->get(route($routeName), ['Accept-Language' => 'fr;q=0.5']);

        $response->assertRedirect(route($routeName, ['locale' => LocaleEnum::FR]));

        session()->flush();

        $response = $this->get(route($routeName), ['Accept-Language' => 'es;q=0.5']);

        $response->assertRedirect(route($routeName, ['locale' => LocaleEnum::default()]));
    })->with('route_names');

    it('404s when redirected to an unknown route', function (): void {
        /** @var FeatureTestCase $this */
        expect(Session::resolve()->getLocale())->toEqual(null);

        $response = $this->get('/nope', ['Accept-Language' => 'fr;q=0.5']);

        $response->assertRedirect();

        expect(Session::resolve()->getLocale())->toEqual(LocaleEnum::FR);

        /** @var RedirectResponse $baseResponse */
        $baseResponse = $response->baseResponse;

        $response = $this->get($baseResponse->getTargetUrl());

        $response->assertStatus(404);

        expect(Session::resolve()->getLocale())->toEqual(LocaleEnum::FR);
    });
});
