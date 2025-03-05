<?php

declare(strict_types=1);

namespace App\Support\GitHub\Resources\Repo\Contents\File;

use App\Support\GitHub\Resources\GitHubResource;

/**
 * @see https://docs.github.com/en/rest/repos/contents?apiVersion=2022-11-28#get-repository-content
 */
final readonly class RepoContentFileData extends GitHubResource
{
    public function __construct(
        public string $name,
        public string $path,
        public string $sha,
        public int $size,
        public string $html_url,
        public string $type,
        public ?string $content,
        public string $encoding,
    ) {
    }

    protected static function attributesToProperties(array $attributes): array
    {
        $properties = parent::attributesToProperties($attributes);

        if (blank($properties['content'])) {
            $properties['content'] = null;
        }

        return $properties;
    }
}
