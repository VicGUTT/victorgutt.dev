<?php

declare(strict_types=1);

namespace Domain\OpenSource\Controllers;

use Domain\OpenSource\Models\OssRelease;
use Domain\OpenSource\Models\OssRepository;
use Domain\OpenSource\Models\OssDocumentation;
use Domain\OpenSource\Pages\OssRepositoryShowPage;

final class OssRepositoryShowController
{
    public function __invoke(string $locale, string $path): OssRepositoryShowPage
    {
        $release = OssRelease::query()
            ->with(['repository', 'documentations'])
            ->findOrFail($path);

        /** @var OssRepository $repository */
        $repository = $release->repository;

        /** @var OssDocumentation $documentation */
        $documentation = $release->documentations->first();

        $release->unsetRelations();

        return OssRepositoryShowPage::new()
            ->withRepository($repository)
            ->withRelease($release)
            ->withDocumentation($documentation);
    }
}
