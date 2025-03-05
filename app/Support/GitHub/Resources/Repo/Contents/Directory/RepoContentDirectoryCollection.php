<?php

declare(strict_types=1);

namespace App\Support\GitHub\Resources\Repo\Contents\Directory;

use App\Support\GitHub\Concerns\GitHubResourceCollection;
use App\Support\GitHub\Resources\Repo\Contents\Directory\RepoContentDirectoryData;

/**
 * @extends GitHubResourceCollection<int, RepoContentDirectoryData>
 */
final class RepoContentDirectoryCollection extends GitHubResourceCollection
{
    // public function whereName(string $name): static
    // {
    //     return $this->where('name', $name);
    // }

    public function whereIsFile(): static
    {
        return $this->where('type', 'file');
    }

    public function whereIsDirectory(): static
    {
        return $this->where('type', 'dir');
    }

    /**
     * Converts items into Resources.
     *
     * @param  array<array-key, mixed>  $items
     * @return array<int, RepoContentDirectoryData>
     */
    protected function arrayableItemsToResource(array $items): array
    {
        return array_reduce($items, static function (array $acc, array|RepoContentDirectoryData $item): array {
            if (!($item instanceof RepoContentDirectoryData)) {
                $item = RepoContentDirectoryData::from($item);
            }

            $acc[] = $item;

            return $acc;
        }, []);
    }
}
