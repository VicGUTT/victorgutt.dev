<?php

declare(strict_types=1);

namespace Domain\Locale\Middlewares;

use Closure;
use Domain\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Illuminate\Http\JsonResponse;
use Domain\Locale\Enums\LocaleEnum;
use Illuminate\Http\RedirectResponse;

final class EnsureLocaleIsUpToDate
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|Illuminate\Http\JsonResponse|RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse
    {
        /* Locale specified by route param
        ------------------------------------------------*/

        $locale = $request->route()?->parameter('locale');

        if (is_string($locale)) {
            $locale = mb_strtolower($locale);
        }

        if (is_string($locale) && LocaleEnum::isSupported($locale)) {
            return $this->setLocaleAndRespond(LocaleEnum::from($locale), $request, $next);
        }

        /* Locale retrievable from storage
        ------------------------------------------------*/

        $locale = $this->tryRetrieveLocaleFromStorage();

        if ($locale) {
            return $this->setLocaleAndTryRedirect($locale, $request);
        }

        /* Locale specified by preferred language
        ------------------------------------------------*/

        $locale = $request->getPreferredLanguage(LocaleEnum::values());

        if ($locale) {
            return $this->setLocaleAndTryRedirect(LocaleEnum::from($locale), $request);
        }

        /* Fallback to default locale
        ------------------------------------------------*/

        return $this->setLocaleAndTryRedirect(LocaleEnum::default(), $request);
    }

    /* Locale modification
    ------------------------------------------------*/

    private function setLocaleAndRespond(LocaleEnum $locale, Request $request, Closure $next): Response|JsonResponse|RedirectResponse
    {
        $this->setLocale($locale);

        return $next($request);
    }

    private function setLocaleAndTryRedirect(LocaleEnum $locale, Request $request): RedirectResponse
    {
        $this->setLocale($locale);

        return $this->tryRedirectWithLocaleFromStorage($request);
    }

    private function setLocale(LocaleEnum $locale): void
    {
        Session::resolve()->setLocale($locale);

        $this->updateLocale($locale);
    }

    private function updateLocale(LocaleEnum $locale): void
    {
        app()->setLocale($locale->value);
    }

    /* Helpers
    ------------------------------------------------*/

    private function tryRedirectWithLocaleFromStorage(Request $request): RedirectResponse
    {
        $locale = $this->tryRetrieveLocaleFromStorage();

        if (!$locale) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $route = $request->route();

        if (!($route instanceof Route)) {
            abort(Response::HTTP_NOT_FOUND);
        }

        // Matched the home route AND requested the home route
        if ($route->uri() === '{locale?}' && $request->path() === '/') {
            return redirect()->route($route->getName(), [
                ...$route->parameters(),
                'locale' => $locale,
            ]);
        }

        // Matched the home route BUT requested a different route

        $uri = $request->uri();
        $uri = $uri->withPath("/{$locale->value}/{$request->path()}");

        return redirect()->to($uri->value());
    }

    private function tryRetrieveLocaleFromStorage(): ?LocaleEnum
    {
        /* From session
        ------------------------------------------------*/

        $locale = Session::resolve()->getLocale();

        if ($locale) {
            return $locale;
        }

        /* From DB
        ------------------------------------------------*/

        // if (user_logged_in_and_locale_retrievable_from_db) {
        //     return ...;
        // }

        /* None found
        ------------------------------------------------*/

        return null;
    }
}
