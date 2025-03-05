<?php

declare(strict_types=1);

namespace App\Support\InertiaPage\Concerns;

use Inertia\Inertia;

trait AsInertiaPageable
{
    public function headDescription(): ?string
    {
        return null;
    }

    public function headMeta(): ?array
    {
        return null;
    }

    public function head(): array
    {
        return array_filter([
            ...($this->headMeta() ?: []),
            'title' => $this->headTitle(),
            'description' => $this->headDescription(),
        ]);
    }

    public function meta(): array
    {
        return [];
    }

    public function pageProps(): array
    {
        return [
            'data' => $this->data(),
            'head' => $this->head(),
            'meta' => function (): array {
                $shared = Inertia::getShared()['meta'] ?? null;

                if (is_callable($shared)) {
                    $shared = app()->call($shared);
                }

                if (empty($shared)) {
                    $shared = [];
                }

                return [
                    ...$shared,
                    ...$this->meta(),
                ];
            },
        ];
    }
}
