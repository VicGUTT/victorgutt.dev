<?php

declare(strict_types=1);

namespace App\Support\Pages;

use Domain\Seo\Support\OgImage;
use App\Support\InertiaPage\InertiaPage;

final class ContactPage extends InertiaPage
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
        return 'contact';
    }

    public function headTitle(): string
    {
        return __('pages/contact.head.title');
    }

    public function headDescription(): string
    {
        return __('pages/contact.head.description');
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
