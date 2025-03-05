<?php

declare(strict_types=1);

namespace Domain\TechStack\Collections;

use Domain\TechStack\Data\TechStackItemData;
use Domain\TechStack\Collections\AbstractTechStackCollection;

/**
 * @extends GitHubResourceCollection<int, TechStackItemData>
 */
final class SecondaryTechStackCollection extends AbstractTechStackCollection
{
    /**
     * Returns the items of the collection.
     */
    protected function data(): array
    {
        return [
            new TechStackItemData(
                key: 'adobe_illustrator',
                label: 'Adobe Illustrator',
                usage_start_year: 2015,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'sass',
                label: 'SASS',
                usage_start_year: 2016,
                usage_end_year: 2022,
            ),
            new TechStackItemData(
                key: 'node',
                label: 'Node',
                usage_start_year: 2017,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'nuxt',
                label: 'Nuxt',
                usage_start_year: 2019,
                usage_end_year: 2023,
            ),
            new TechStackItemData(
                key: 'phpunit',
                label: 'PHPUnit',
                usage_start_year: 2019,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'Postcss',
                label: 'PostCSS',
                usage_start_year: 2020,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'alpinejs',
                label: 'AlpineJS',
                usage_start_year: 2020,
                usage_end_year: 2023,
            ),
            new TechStackItemData(
                key: 'livewire',
                label: 'Livewire',
                usage_start_year: 2020,
                usage_end_year: 2020,
            ),
            new TechStackItemData(
                key: 'figma',
                label: 'Figma',
                usage_start_year: 2022,
                usage_end_year: null,
            ),
        ];
    }
}
