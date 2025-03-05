<?php

declare(strict_types=1);

namespace App\Support\Action;

use App\Support\Resolvable\Contracts\Resolvable;
use App\Support\Resolvable\Concerns\CanBeResolved;

abstract readonly class Action implements Resolvable
{
    use CanBeResolved;
}
