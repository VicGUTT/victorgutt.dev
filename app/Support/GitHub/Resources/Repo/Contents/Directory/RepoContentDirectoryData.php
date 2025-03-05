<?php

declare(strict_types=1);

namespace App\Support\GitHub\Resources\Repo\Contents\Directory;

use App\Support\GitHub\Resources\GitHubResource;

/**
 * @see https://docs.github.com/en/rest/repos/contents?apiVersion=2022-11-28#get-repository-content
 */
final readonly class RepoContentDirectoryData extends GitHubResource
{
    public function __construct(
        public string $name,
        public string $path,
        public string $sha,
        public int $size,
        public string $html_url,
        public string $type,
    ) {
    }

    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    public function isDirectory(): bool
    {
        return $this->type === 'dir';
    }
}
