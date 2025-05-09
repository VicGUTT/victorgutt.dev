<?php

declare(strict_types=1);

namespace Domain\Locale\Enums;

use VicGutt\PhpEnhancedEnum\Concerns\Enumerable;
use VicGutt\PhpEnhancedEnum\Contracts\EnumerableContract;

enum LocaleEnum: string implements EnumerableContract
{
    use Enumerable;

    case EN = 'en';
    case FR = 'fr';

    public static function default(): static
    {
        return static::EN;
    }

    public static function fallback(): static
    {
        return static::EN;
    }

    public static function isSupported(string $value): bool
    {
        return (bool) static::tryFrom($value);
    }
}
