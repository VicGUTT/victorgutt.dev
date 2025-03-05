<?php

declare(strict_types=1);

namespace App\Support\Pages;

use App\Support\InertiaPage\InertiaPage;

final class ResumePage extends InertiaPage
{
    public function __construct(private array $data = [])
    {
    }

    public static function new(array $data = []): static
    {
        return new static($data);
    }

    public function pageComponentPath(): string
    {
        return 'resume';
    }

    public function headTitle(): string
    {
        return __('pages/resume.head.title');
    }

    public function data(): array
    {
        return $this->data;
    }
}
