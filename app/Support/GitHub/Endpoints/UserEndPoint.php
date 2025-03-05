<?php

declare(strict_types=1);

namespace App\Support\GitHub\Endpoints;

use App\Support\GitHub\Contracts\GitHubApiEndPoint;
use App\Support\GitHub\Concerns\AsGitHubApiEndPoint;
use App\Support\GitHub\Resources\User\Repos\UserRepoCollection;

final readonly class UserEndPoint implements GitHubApiEndPoint
{
    use AsGitHubApiEndPoint;

    /**
     * @see https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#list-repositories-for-the-authenticated-user
     */
    public function repos(): UserRepoCollection
    {
        $items = $this->client->http()->get('/user/repos', [
            'visibility' => 'public',
            'affiliation' => 'owner',
            'sort' => 'created',
            'direction' => 'asc',
            'per_page' => 100,
        ])->json();

        return UserRepoCollection::make($items);
    }
}
