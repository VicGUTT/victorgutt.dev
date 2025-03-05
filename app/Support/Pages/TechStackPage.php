<?php

declare(strict_types=1);

namespace App\Support\Pages;

use Domain\Seo\Support\OgImage;
use App\Support\InertiaPage\InertiaPage;

final class TechStackPage extends InertiaPage
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
        return 'tech_stack';
    }

    public function headTitle(): string
    {
        return __('page_links.tech_stack.label');
    }

    public function headDescription(): null
    {
        return null;
    }

    public function headMeta(): array
    {
        return [
            'og' => [
                'image' => OgImage::resolve()->current(),
            ],
        ];
    }

    public function data(): array
    {
        return $this->data;
    }
}
