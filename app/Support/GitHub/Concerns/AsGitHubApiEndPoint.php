<?php

declare(strict_types=1);

namespace App\Support\GitHub\Concerns;

use App\Support\GitHub\GitHubClient;
use App\Support\Resolvable\Concerns\CanBeResolved;

trait AsGitHubApiEndPoint
{
    use CanBeResolved;

    public function __construct(protected readonly GitHubClient $client)
    {
    }
}
