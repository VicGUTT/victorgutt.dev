<?php

declare(strict_types=1);

namespace App\Support\Pages;

use Closure;
use Domain\Seo\Support\OgImage;
use App\Support\InertiaPage\InertiaPage;

final class HomePage extends InertiaPage
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
        return 'home/home';
    }

    public function headTitle(): string
    {
        return __('terms.web_developer') . ' - ' . __('terms.web_designer');
    }

    public function headDescription(): string
    {
        return __('pages/home.head.description');
    }

    public function headMeta(): array
    {
        return [
            'og' => [
                'image' => OgImage::resolve()->current(),
            ],
        ];
    }

    public function withProjects(Closure $callback): static
    {
        $this->data['projects'] = $callback;

        return $this;
    }

    public function withRepositories(Closure $callback): static
    {
        $this->data['repositories'] = $callback;

        return $this;
    }

    public function withTechStack(Closure $callback): static
    {
        $this->data['tech_stacks'] = $callback;

        return $this;
    }

    public function data(): array
    {
        return $this->data;
    }
}
