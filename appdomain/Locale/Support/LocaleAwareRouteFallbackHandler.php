<?php

declare(strict_types=1);

namespace Domain\Locale\Support;

use Domain\Session\Session;
use Illuminate\Http\Request;
use Domain\Locale\Enums\LocaleEnum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class LocaleAwareRouteFallbackHandler
{
    public function __invoke(Request $request, string $path): RedirectResponse
    {
        $locale = Session::resolve()->getLocale() ?: $request->getPreferredLanguage(LocaleEnum::values()) ?: LocaleEnum::default();

        if ($locale instanceof LocaleEnum) {
            $locale = $locale->value;
        }

        $matched = Route::getRoutes()->match(
            Request::createFromBase(
                SymfonyRequest::create(
                    "/{$locale}/{$path}",
                ),
            ),
        );

        if ($matched->isFallback) {
            throw new NotFoundHttpException(sprintf(
                'The route %s could not be found.',
                $path,
            ));
        }

        return redirect()->to("/{$locale}/{$path}");
    }
}
