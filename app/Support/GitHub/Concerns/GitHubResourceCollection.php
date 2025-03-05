<?php

declare(strict_types=1);

namespace App\Support\GitHub\Concerns;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use App\Support\GitHub\Resources\GitHubResource;

/**
 * @template TKey of array-key
 * @template TValue of GitHubResource
 *
 * @extends Collection<TKey, TValue>
 * @phpstan-consistent-constructor
 */
abstract class GitHubResourceCollection extends Collection
{
    /**
     * Create a new collection.
     *
     * @param  Arrayable<TKey, TValue>|Arrayable<array-key, mixed>|iterable<TKey, TValue>|iterable<array-key, mixed>|null  $items
     * @return void
     */
    public function __construct($items = [])
    {
        $this->items = $this->arrayableItemsToResource($this->getArrayableItems($items));
    }

    /**
     * Converts items into GitHubResources.
     *
     * @param  array<array-key, mixed>  $items
     * @return array<TKey, TValue>
     */
    abstract protected function arrayableItemsToResource(array $items): array;

    /**
     * Get the collection of items as a plain array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_map(static fn (GitHubResource $item): array => $item->toArray(), $this->items);
    }
}
