<?php

declare(strict_types=1);

namespace App\Support\GitHub\Resources\Repo\Releases;

use App\Support\GitHub\Resources\GitHubResource;

/**
 * @see https://docs.github.com/en/rest/releases/releases?apiVersion=2022-11-28#list-releases
 */
final readonly class RepoReleaseData extends GitHubResource
{
    public function __construct(
        public int $id,
        public string $html_url,
        public string $tag_name,
        public string $name,
        public ?string $body,
        public bool $draft,
        public bool $prerelease,
        public string $created_at,
        public string $published_at,
    ) {
    }

    protected static function attributesToProperties(array $attributes): array
    {
        $properties = parent::attributesToProperties($attributes);

        if (blank($properties['body'])) {
            $properties['body'] = null;
        }

        return $properties;
    }
}
