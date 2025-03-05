<?php

declare(strict_types=1);

namespace Domain\Analytics\Controllers;

use Throwable;
use Domain\Analytics\Pirsch\Pirsch;
use Domain\Analytics\Requests\InitialVisitRequest;

final class InitialVisitController
{
    public function __invoke(InitialVisitRequest $request): void
    {
        try {
            Pirsch::resolve()->trackHit(array_filter([
                'url' => $request->href(),
                'referrer' => $request->referrer(),
            ]));
        } catch (Throwable $th) {
            report($th);
        }
    }
}
