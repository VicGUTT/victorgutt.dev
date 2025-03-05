<?php

declare(strict_types=1);

namespace Domain\Session;

use Domain\Locale\Enums\LocaleEnum;
use Domain\Session\Enums\SessionEnum;
use App\Support\Resolvable\Contracts\Resolvable;
use App\Support\Resolvable\Concerns\CanBeResolved;

final class Session implements Resolvable
{
    use CanBeResolved;

    /* General
    ------------------------------------------------*/

    public function has(SessionEnum $enum): bool
    {
        return session()->has($enum->value);
    }

    public function get(SessionEnum $enum): mixed
    {
        return session()->get($enum->value);
    }

    public function set(SessionEnum $enum, mixed $value): void
    {
        session()->put($enum->value, $value);
    }

    /* Locale
    ------------------------------------------------*/

    public function hasLocale(): bool
    {
        return $this->has(SessionEnum::LOCALE);
    }

    public function getLocale(): ?LocaleEnum
    {
        $locale = $this->get(SessionEnum::LOCALE);

        if (!$locale) {
            return null;
        }

        return LocaleEnum::from($locale);
    }

    public function setLocale(LocaleEnum $locale): void
    {
        $this->set(SessionEnum::LOCALE, $locale->value);
    }
}
