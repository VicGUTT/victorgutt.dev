<?php

declare(strict_types=1);

namespace Domain\OpenSource\Pages;

use Domain\Seo\Support\OgImage;
use App\Support\InertiaPage\InertiaPage;
use Domain\OpenSource\Models\OssRelease;
use Domain\OpenSource\Models\OssRepository;
use Domain\OpenSource\Models\OssDocumentation;

final class OssRepositoryShowPage extends InertiaPage
{
    private OssRepository $repository;

    public function __construct(private array $data = [])
    {
    }

    public static function new(array $data = []): static
    {
        return new static($data);
    }

    public function pageComponentPath(): string
    {
        return 'oss/show';
    }

    public function headTitle(): string
    {
        return str($this->repository->full_name)
            ->after('/')
            ->replace('-', ' ')
            ->title()
            ->value();
    }

    public function headDescription(): string
    {
        $languages = collect($this->repository->languages ?: [])->keys()->implode(', ');
        $topics = collect($this->repository->topics ?: [])
            ->map(static fn (string $topic): string => str($topic)->title()->value())
            ->implode(', ');

        return str($this->repository->description ?: '')
            ->finish('.')
            ->append(filled($languages) ? " {$languages}" : '')
            ->append(filled($languages) && filled($topics) ? ',' : '')
            ->append(filled($topics) ? " {$topics}" : '')
            ->limit(140 - 1, '', true)
            ->trim(',')
            ->finish('.')
            ->value();
    }

    public function headMeta(): array
    {
        return [
            'og' => [
                'image' => OgImage::resolve()->current(),
            ],
        ];
    }

    public function withRepository(OssRepository $repository): static
    {
        $this->repository = $repository;

        return $this;
    }

    public function withRelease(OssRelease $release): static
    {
        $this->data['release'] = $release;

        return $this;
    }

    public function withDocumentation(OssDocumentation $documentation): static
    {
        $this->data['documentation'] = $documentation;

        return $this;
    }

    public function data(): array
    {
        return [
            ...$this->data,
            'repository' => $this->repository,
        ];
    }
}
