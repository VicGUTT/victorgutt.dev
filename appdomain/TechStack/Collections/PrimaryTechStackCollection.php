<?php

declare(strict_types=1);

namespace Domain\TechStack\Collections;

use Domain\TechStack\Data\TechStackItemData;
use Domain\TechStack\Collections\AbstractTechStackCollection;

/**
 * @extends GitHubResourceCollection<int, TechStackItemData>
 */
final class PrimaryTechStackCollection extends AbstractTechStackCollection
{
    /**
     * Returns the items of the collection.
     */
    protected function data(): array
    {
        return [
            new TechStackItemData(
                key: 'html',
                label: 'HTML',
                usage_start_year: 2015,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'css',
                label: 'CSS',
                usage_start_year: 2015,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'php',
                label: 'PHP',
                usage_start_year: 2016,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'mysql',
                label: 'MySQL',
                usage_start_year: 2016,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'javascript',
                label: 'JavaScript',
                usage_start_year: 2016,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'laravel',
                label: 'Laravel',
                usage_start_year: 2017,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'vue',
                label: 'Vue',
                usage_start_year: 2018,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'sqlite',
                label: 'SQLite',
                usage_start_year: 2018,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'inertia',
                label: 'Inertia',
                usage_start_year: 2019,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'git',
                label: 'Git',
                usage_start_year: 2019,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'tailwindcss',
                label: 'Tailwind CSS',
                usage_start_year: 2020,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'nginx',
                label: 'Nginx',
                usage_start_year: 2020,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'typescript',
                label: 'TypeScript',
                usage_start_year: 2021,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'vite',
                label: 'Vite',
                usage_start_year: 2021,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'vitest',
                label: 'Vitest',
                usage_start_year: 2021,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'playwright',
                label: 'Playwright',
                usage_start_year: 2022,
                usage_end_year: null,
            ),
            new TechStackItemData(
                key: 'pestphp',
                label: 'PestPHP',
                usage_start_year: 2022,
                usage_end_year: null,
            ),
        ];
    }
}
