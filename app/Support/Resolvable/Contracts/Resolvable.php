<?php

declare(strict_types=1);

namespace App\Support\Resolvable\Contracts;

interface Resolvable
{
    public static function resolve(array $parameters = []): static;
}
