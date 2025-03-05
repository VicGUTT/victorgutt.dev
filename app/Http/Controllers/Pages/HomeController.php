<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pages;

use App\Support\Pages\HomePage;
use Domain\TechStack\TechStack;
use Illuminate\Database\Eloquent\Builder;
use Domain\OpenSource\Queries\GetRepositoriesQuery;
use Domain\TechStack\Collections\PrimaryTechStackCollection;

final class HomeController
{
    public function __invoke(): HomePage
    {
        return HomePage::new()
            ->withProjects($this->getProjects(...))
            ->withRepositories($this->getRepositories(...))
            ->withTechStack($this->getTechStacks(...));
    }

    private function getProjects(): array
    {
        return __('pages/project.sections.paused.data');
    }

    private function getRepositories(): array
    {
        return GetRepositoriesQuery::resolve()->execute(static fn (Builder $query) => $query->take(6));
    }

    private function getTechStacks(): PrimaryTechStackCollection
    {
        return TechStack::resolve()->primary();
    }
}
