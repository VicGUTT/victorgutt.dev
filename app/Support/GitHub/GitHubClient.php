<?php

declare(strict_types=1);

namespace App\Support\GitHub;

use Closure;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use App\Support\Resolvable\Contracts\Resolvable;
use App\Support\Resolvable\Concerns\CanBeResolved;

final class GitHubClient implements Resolvable
{
    use CanBeResolved;

    public const BASE_URL = 'https://api.github.com';

    /**
     * Request setup.
     */
    public function http(): PendingRequest
    {
        $request = Http::baseUrl(static::BASE_URL)
            ->withToken(config('services.github.access_token'))
            ->accept('application/vnd.github+json')
            ->asJson()
            ->throw();

        return $this->setupRetry($request);
    }

    protected function setupRetry(PendingRequest $request): PendingRequest
    {
        return $request->retry(2, 0, $this->makeTooManyRequestsRetryHandler());
    }

    protected function makeTooManyRequestsRetryHandler(): Closure
    {
        /**
         * @see https://docs.github.com/en/rest/using-the-rest-api/best-practices-for-using-the-rest-api?apiVersion=2022-11-28#handle-rate-limit-errors-appropriately
         */
        return static function (Exception $exception, PendingRequest $request): bool {
            if (!($exception instanceof RequestException)) {
                return false;
            }

            $retryAfter = (int) $exception->response->header('Retry-After');

            if ($retryAfter) {
                sleep($retryAfter);

                return true;
            }

            $remaining = (int) $exception->response->header('X-Ratelimit-Remaining');
            $reset = (int) $exception->response->header('X-Ratelimit-Reset');

            if ($remaining === 0 && $reset) {
                sleep((int) Carbon::createFromTimestamp($reset)->diffInSeconds());

                return true;
            }

            return false;
        };
    }
}
