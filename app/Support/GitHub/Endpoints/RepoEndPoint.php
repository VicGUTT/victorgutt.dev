<?php

declare(strict_types=1);

namespace App\Support\GitHub\Endpoints;

use Illuminate\Support\Collection;
use App\Support\GitHub\Contracts\GitHubApiEndPoint;
use App\Support\GitHub\Concerns\AsGitHubApiEndPoint;
use App\Support\GitHub\Resources\Repo\Releases\RepoReleaseData;
use App\Support\GitHub\Resources\Repo\Releases\RepoReleaseCollection;
use App\Support\GitHub\Resources\Repo\Contents\File\RepoContentFileData;
use App\Support\GitHub\Resources\Repo\Contents\Directory\RepoContentDirectoryCollection;

/**
 * @see https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28
 */
final readonly class RepoEndPoint implements GitHubApiEndPoint
{
    use AsGitHubApiEndPoint;

    // /**
    //  * @see https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#get-all-repository-topics
    //  *
    //  * @return object{
    //  *    names: Collection<int, string>,
    //  * }
    //  */
    // public function topics(string $repo, string $owner = 'vicgutt'): object
    // {
    //     $item = (object) $this->client->http()->get("/repos/{$owner}/{$repo}/topics", [
    //         'per_page' => 100,
    //     ])->json();

    //     $item->names = collect($item->names);

    //     return $item;
    // }

    /**
     * @see https://docs.github.com/en/rest/releases/releases?apiVersion=2022-11-28#list-releases
     */
    public function releases(string $repo, string $owner = 'vicgutt'): RepoReleaseCollection
    {
        $items = $this->client->http()->get("/repos/{$owner}/{$repo}/releases", [
            'per_page' => 100,
        ])->json();

        return RepoReleaseCollection::make($items);
    }

    /**
     * @see https://docs.github.com/en/rest/releases/releases?apiVersion=2022-11-28#get-the-latest-release
     */
    public function latestRelease(string $repo, string $owner = 'vicgutt'): RepoReleaseData
    {
        $item = $this->client->http()->get("/repos/{$owner}/{$repo}/releases/latest", [
            'per_page' => 100,
        ])->json();

        return RepoReleaseData::from($item);
    }

    /**
     * @see https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#list-repository-languages
     *
     * @return Collection<string, int>
     */
    public function languages(string $repo, string $owner = 'vicgutt'): Collection
    {
        /** @var array<string, int> $items */
        $items = $this->client->http()->get("/repos/{$owner}/{$repo}/languages", [
            'per_page' => 100,
        ])->json();

        return collect($items);
    }

    /**
     * @see https://docs.github.com/en/rest/repos/contents?apiVersion=2022-11-28#get-repository-content
     *
     * @param array{ref?: string} $params
     */
    public function contents(string $repo, string $path, string $owner = 'vicgutt', array $params = []): RepoContentDirectoryCollection|RepoContentFileData
    {
        $data = $this->client->http()->get("/repos/{$owner}/{$repo}/contents/{$path}", [
            'per_page' => 100,
            ...$params,
        ])->json();

        if (array_is_list($data)) {
            return RepoContentDirectoryCollection::make($data);
        }

        return RepoContentFileData::from($data);
    }

    /**
     * @see https://docs.github.com/en/rest/repos/contents?apiVersion=2022-11-28#get-repository-content
     *
     * @param array{ref?: string} $params
     */
    public function contentsAsDirectory(string $repo, string $path, string $owner = 'vicgutt', array $params = []): RepoContentDirectoryCollection
    {
        /** @var RepoContentDirectoryCollection $data */
        $data = $this->contents($repo, $path, $owner, $params);

        return $data;
    }

    /**
     * @see https://docs.github.com/en/rest/repos/contents?apiVersion=2022-11-28#get-repository-content
     *
     * @param array{ref?: string} $params
     */
    public function contentsAsFile(string $repo, string $path, string $owner = 'vicgutt', array $params = []): RepoContentFileData
    {
        /** @var RepoContentFileData $data */
        $data = $this->contents($repo, $path, $owner, $params);

        return $data;
    }
}
