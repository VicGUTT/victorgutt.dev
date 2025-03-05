<?php

declare(strict_types=1);

namespace Domain\OpenSource\Controllers;

use Domain\OpenSource\Pages\OssRepositoryIndexPage;
use Domain\OpenSource\Queries\GetRepositoriesQuery;

final class OssRepositoryIndexController
{
    public function __invoke(): OssRepositoryIndexPage
    {
        return OssRepositoryIndexPage::new()->withRepositories($this->getRepositories(...));
    }

    private function getRepositories(): array
    {
        return GetRepositoriesQuery::resolve()->execute();
    }
}
