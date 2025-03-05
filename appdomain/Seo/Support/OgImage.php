<?php

declare(strict_types=1);

namespace Domain\Seo\Support;

use Domain\Locale\Enums\LocaleEnum;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use App\Support\Resolvable\Contracts\Resolvable;
use App\Support\Resolvable\Concerns\CanBeResolved;

final class OgImage implements Resolvable
{
    use CanBeResolved;

    public function url(LocaleEnum|string $locale, string $routeName, string $urlPath): ?string
    {
        if (is_string($locale)) {
            $locale = LocaleEnum::from($locale);
        }

        $manifest = $this->manifest();

        $path = $manifest[$locale->value][$routeName][$urlPath]['path']['relative'] ?? null;

        if (!$path) {
            return null;
        }

        return $this->storage()->temporaryUrl($path, now()->addHour());
    }

    public function current(): ?string
    {
        return $this->url(app()->getLocale(), Route::currentRouteName(), request()->path());
    }

    /* Helpers
    ------------------------------------------------*/

    private function storage(): FilesystemAdapter
    {
        return Storage::disk('og_images');
    }

    /**
     * @return array <
     *    string,
     *    array <
     *        string,
     *        array <
     *            string,
     *            array {
     *                digest: string,
     *                path: array {
     *                    full: string,
     *                    relative: string,
     *                },
     *            },
     *        >
     *    >
     * >
     */
    private function manifest(): array
    {
        if (!$this->storage()->exists('manifest.json')) {
            return [];
        }

        return json_decode($this->storage()->get('manifest.json'), true);
    }
}
