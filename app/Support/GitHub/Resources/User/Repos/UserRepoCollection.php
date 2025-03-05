<?php

declare(strict_types=1);

namespace App\Support\GitHub\Resources\User\Repos;

use App\Support\GitHub\Concerns\GitHubResourceCollection;
use App\Support\GitHub\Resources\User\Repos\UserRepoData;

/**
 * @extends GitHubResourceCollection<int, UserRepoData>
 */
final class UserRepoCollection extends GitHubResourceCollection
{
    // public function whereName(string $name): static
    // {
    //     return $this->where('name', $name);
    // }

    public function whereIsPublic(): static
    {
        return $this->where('visibility', 'public');
    }

    public function whereEnabled(): static
    {
        return $this->where('disabled', false);
    }

    public function whereNotForked(): static
    {
        return $this->where('fork', false);
    }

    public function whereNotIgnored(): static
    {
        return $this->whereNotIn('name', [
            'alpine-morph-debug',
        ]);
    }

    /**
     * Converts items into Resources.
     *
     * @param  array<array-key, mixed>  $items
     * @return array<int, UserRepoData>
     */
    protected function arrayableItemsToResource(array $items): array
    {
        return array_reduce($items, static function (array $acc, array|UserRepoData $item): array {
            if (!($item instanceof UserRepoData)) {
                $item = UserRepoData::from($item);
            }

            $acc[] = $item;

            return $acc;
        }, []);
    }
}
