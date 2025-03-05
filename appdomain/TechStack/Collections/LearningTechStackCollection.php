<?php

declare(strict_types=1);

namespace Domain\TechStack\Collections;

use Domain\TechStack\Data\TechStackItemData;
use Domain\TechStack\Collections\AbstractTechStackCollection;

/**
 * @extends GitHubResourceCollection<int, TechStackItemData>
 */
final class LearningTechStackCollection extends AbstractTechStackCollection
{
    /**
     * Returns the items of the collection.
     */
    protected function data(): array
    {
        return [
            new TechStackItemData(
                key: 'go',
                label: 'Go',
                usage_start_year: null,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'postgresql',
                label: 'PostgreSQL',
                usage_start_year: null,
                usage_end_year: null,
            ),
        ];
    }
}
