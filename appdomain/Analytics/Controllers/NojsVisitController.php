<?php

declare(strict_types=1);

namespace Domain\Analytics\Controllers;

use Throwable;
use Domain\Analytics\Pirsch\Pirsch;
use Domain\Analytics\Requests\NojsVisitRequest;

final class NojsVisitController
{
    public function __invoke(NojsVisitRequest $request, string $token): void
    {
        if (!Pirsch::resolve()->enabled()) {
            return;
        }

        try {
            Pirsch::resolve()->trackHit(array_filter([
                'url' => $request->query('href'),
                'referrer' => $request->query('referrer'),
                'tags' => [
                    'no_js' => true,
                ],
            ]));
        } catch (Throwable $th) {
            report($th);
        }
    }
}
