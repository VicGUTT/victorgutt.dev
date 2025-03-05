<?php

declare(strict_types=1);

namespace Domain\Seo\Controllers\Dev;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Support\InertiaPage\InertiaPage;
use Inertia\Response as InertiaResponse;

final class OpenGraphController
{
    public function __invoke(Request $request, string $locale, string $routeName): InertiaResponse
    {
        $route = Route::getRoutes()->getByName($routeName);

        if (!$route) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $controller = app($route->action['controller']);

        /** @var InertiaPage $page */
        $page = app()->call($controller->__invoke(...), [
            'locale' => $locale,
            ...$request->input(),
        ]);

        $props = $page->pageProps();

        $props['data'] = [
            ...$props['data'],
            '__dev__og' => [
                'component_path' => $page->pageComponentPath(),
            ],
        ];

        return Inertia::render('_dev/open_graph', $props);
    }
}
