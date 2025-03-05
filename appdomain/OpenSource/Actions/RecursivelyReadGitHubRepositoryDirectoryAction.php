<?php

declare(strict_types=1);

namespace Domain\OpenSource\Actions;

use Generator;
use App\Support\Action\Action;
use App\Support\GitHub\GitHub;
use Illuminate\Support\Collection;
use App\Support\GitHub\Resources\Repo\Contents\Directory\RepoContentDirectoryData;
use App\Support\GitHub\Resources\Repo\Contents\Directory\RepoContentDirectoryCollection;

final readonly class RecursivelyReadGitHubRepositoryDirectoryAction extends Action
{
    /**
     * @return Generator<int, RepoContentDirectoryData>
     */
    public function execute(string $repositoryName, string $ref, string $path): Generator
    {
        do {
            /** @var Collection<string, RepoContentDirectoryData> $directories */
            /** @var Collection<string, RepoContentDirectoryData> $files */
            [$directories, $files] = $this
                ->fetch($repositoryName, $ref, $path)
                ->toBase()
                ->partition('type', 'dir');

            while ($file = $files->shift()) {
                yield $file;
            }

            while ($directory = $directories->shift()) {
                $childFiles = $this->execute($repositoryName, $ref, $directory->path);

                foreach ($childFiles as $childFile) {
                    yield $childFile;
                }
            }
        } while ($directories->isNotEmpty());
    }

    private function fetch(string $repositoryName, string $ref, string $path): RepoContentDirectoryCollection
    {
        return GitHub::repo()->contentsAsDirectory($repositoryName, $path, params: [
            'ref' => $ref,
        ]);
    }
}
