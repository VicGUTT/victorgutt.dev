<?php

declare(strict_types=1);

namespace App\Support\Resolvable\Concerns;

trait CanBeResolved
{
    public static function resolve(array $parameters = []): static
    {
        return app(static::class, $parameters);
    }
}
