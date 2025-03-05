<?php

declare(strict_types=1);

namespace Domain\TechStack\Collections;

use Illuminate\Support\Collection;
use Domain\TechStack\Data\TechStackItemData;

/**
 * @template TKey of array-key
 * @template TValue of TechStackItemData
 *
 * @extends Collection<TKey, TValue>
 * @phpstan-consistent-constructor
 */
abstract class AbstractTechStackCollection extends Collection
{
    /**
     * Create a new collection.
     *
     * @return void
     */
    public function __construct()
    {
        $this->items = $this->arrayableItemsToResource($this->getArrayableItems($this->data()));
    }

    /**
     * Returns the items of the collection.
     */
    abstract protected function data(): array;

    /**
     * Converts items into Resources.
     *
     * @param  array<array-key, mixed>  $items
     * @return array<int, UserRepoData>
     */
    protected function arrayableItemsToResource(array $items): array
    {
        return array_reduce($items, static function (array $acc, array|TechStackItemData $item): array {
            if (!($item instanceof TechStackItemData)) {
                $item = TechStackItemData::from($item);
            }

            $acc[] = $item;

            return $acc;
        }, []);
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_map(static fn (TechStackItemData $item): array => $item->toArray(), $this->items);
    }
}
