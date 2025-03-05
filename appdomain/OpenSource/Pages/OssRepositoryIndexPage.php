<?php

declare(strict_types=1);

namespace Domain\OpenSource\Pages;

use Closure;
use Domain\Seo\Support\OgImage;
use App\Support\InertiaPage\InertiaPage;

final class OssRepositoryIndexPage extends InertiaPage
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
        return 'oss/index';
    }

    public function headTitle(): string
    {
        return __('pages/oss.index.title');
    }

    public function headDescription(): string
    {
        return __('pages/oss.index.description');
    }

    public function headMeta(): array
    {
        return [
            'og' => [
                'image' => OgImage::resolve()->current(),
            ],
        ];
    }

    public function withRepositories(Closure $callback): static
    {
        $this->data['repositories'] = $callback;

        return $this;
    }

    public function data(): array
    {
        return $this->data;
    }
}
