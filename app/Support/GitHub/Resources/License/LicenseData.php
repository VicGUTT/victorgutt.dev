<?php

declare(strict_types=1);

namespace App\Support\GitHub\Resources\License;

use App\Support\GitHub\Resources\GitHubResource;

final readonly class LicenseData extends GitHubResource
{
    public function __construct(
        public string $key,
        public string $name,
    ) {
    }
}
