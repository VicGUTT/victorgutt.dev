<?php

declare(strict_types=1);

namespace App\Support\GitHub;

use App\Support\GitHub\GitHubClient;
use App\Support\GitHub\Endpoints\RepoEndPoint;
use App\Support\GitHub\Endpoints\UserEndPoint;

final class GitHub
{
    public static function client(): GitHubClient
    {
        return GitHubClient::resolve();
    }

    public static function user(): UserEndPoint
    {
        return UserEndPoint::resolve(['client' => static::client()]);
    }

    public static function repo(): RepoEndPoint
    {
        return RepoEndPoint::resolve(['client' => static::client()]);
    }
}
