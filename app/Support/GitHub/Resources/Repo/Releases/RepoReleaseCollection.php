<?php

declare(strict_types=1);

namespace App\Support\GitHub\Resources\Repo\Releases;

use App\Support\GitHub\Concerns\GitHubResourceCollection;
use App\Support\GitHub\Resources\Repo\Releases\RepoReleaseData;

/**
 * @extends GitHubResourceCollection<int, RepoReleaseData>
 */
final class RepoReleaseCollection extends GitHubResourceCollection
{
    // public function whereName(string $name): static
    // {
    //     return $this->where('name', $name);
    // }

    public function whereNotDraft(): static
    {
        return $this->where('draft', false);
    }

    public function whereIsNotPrerelease(): static
    {
        return $this->where('prerelease', false);
    }

    /**
     * Converts items into Resources.
     *
     * @param  array<array-key, mixed>  $items
     * @return array<int, RepoReleaseData>
     */
    protected function arrayableItemsToResource(array $items): array
    {
        return array_reduce($items, static function (array $acc, array|RepoReleaseData $item): array {
            if (!($item instanceof RepoReleaseData)) {
                $item = RepoReleaseData::from($item);
            }

            $acc[] = $item;

            return $acc;
        }, []);
    }
}
