<?php

declare(strict_types=1);

namespace Domain\TechStack\Collections;

use Domain\TechStack\Data\TechStackItemData;
use Domain\TechStack\Collections\AbstractTechStackCollection;

/**
 * @extends GitHubResourceCollection<int, TechStackItemData>
 */
final class HaveUsedTechStackCollection extends AbstractTechStackCollection
{
    /**
     * Returns the items of the collection.
     */
    protected function data(): array
    {
        return [
            new TechStackItemData(
                key: 'adobe_premiere_pro',
                label: 'Adobe Premiere Pro',
                usage_start_year: 2008,
                usage_end_year: 2016,
            ),
            new TechStackItemData(
                key: 'adobe_after_effects',
                label: 'Adobe After Effects',
                usage_start_year: 2012,
                usage_end_year: 2016,
            ),
            new TechStackItemData(
                key: 'adobe_photoshop',
                label: 'Adobe Photoshop',
                usage_start_year: 2013,
                usage_end_year: 2019,
            ),
            new TechStackItemData(
                key: 'bootstrapcss',
                label: 'Bootstrap CSS',
                usage_start_year: 2016,
                usage_end_year: 2020,
            ),
            new TechStackItemData(
                key: 'apache',
                label: 'Apache',
                usage_start_year: 2016,
                usage_end_year: 2020,
            ),
            new TechStackItemData(
                key: 'webpack',
                label: 'Webpack',
                usage_start_year: 2017,
                usage_end_year: 2023,
            ),
            new TechStackItemData(
                key: 'adobe_xd',
                label: 'Adobe XD',
                usage_start_year: 2018,
                usage_end_year: 2019,
            ),
            new TechStackItemData(
                key: 'jest',
                label: 'Jest',
                usage_start_year: 2020,
                usage_end_year: 2021,
            ),
            new TechStackItemData(
                key: 'docker',
                label: 'Docker',
                usage_start_year: 2020,
                usage_end_year: 2022,
            ),
            new TechStackItemData(
                key: 'react',
                label: 'React',
                usage_start_year: 2021,
                usage_end_year: 2021,
            ),
            new TechStackItemData(
                key: 'web_components',
                label: 'Web components',
                usage_start_year: 2021,
                usage_end_year: 2022,
            ),
            new TechStackItemData(
                key: 'angularjs',
                label: 'AngularJS (v1)',
                usage_start_year: 2022,
                usage_end_year: 2022,
            ),
            new TechStackItemData(
                key: 'angular',
                label: 'Angular (v12+)',
                usage_start_year: 2022,
                usage_end_year: 2022,
            ),
            new TechStackItemData(
                key: 'ionic',
                label: 'Ionic',
                usage_start_year: 2022,
                usage_end_year: 2022,
            ),
            new TechStackItemData(
                key: 'cypress',
                label: 'Cypress',
                usage_start_year: 2021,
                usage_end_year: 2021,
            ),
            new TechStackItemData(
                key: 'aws',
                label: 'AWS',
                usage_start_year: 2022,
                usage_end_year: 2023,
            ),
        ];
    }
}
