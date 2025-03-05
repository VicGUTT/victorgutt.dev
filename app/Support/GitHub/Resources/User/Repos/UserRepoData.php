<?php

declare(strict_types=1);

namespace App\Support\GitHub\Resources\User\Repos;

use App\Support\GitHub\Resources\GitHubResource;
use App\Support\GitHub\Resources\License\LicenseData;

/**
 * @see https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#list-repositories-for-the-authenticated-user
 */
final readonly class UserRepoData extends GitHubResource
{
    public function __construct(
        public int $id,
        public string $name,
        public string $full_name,
        public bool $private,
        public string $html_url,
        public ?string $description,
        public bool $fork,
        public ?string $homepage,
        public ?string $language,
        public int $size,
        /**
         * @var array<int, string>
         */
        public array $topics,
        public bool $archived,
        public bool $disabled,
        public string $visibility,
        public string $pushed_at,
        public string $created_at,
        public string $updated_at,
        public ?LicenseData $license,
    ) {
    }
}
