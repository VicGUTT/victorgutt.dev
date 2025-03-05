<?php

declare(strict_types=1);

namespace Domain\OpenSource\Queries;

use Closure;
use App\Support\Query\Query;
use Illuminate\Database\Eloquent\Builder;
use Domain\OpenSource\Models\OssRepository;
use Illuminate\Database\Eloquent\Relations\HasOne;

// use Illuminate\Database\Eloquent\Collection as EloquentCollection;

final readonly class GetRepositoriesQuery extends Query
{
    /**
     * Note: Having to manually decode the `topics` attribute
     * due to some unexplicable issue that arises in production.
     * I have not yet taken the time to investigate.
     */
    public function execute(Closure|null $callback = null): array
    {
        if (!$callback) {
            $callback = static fn (Builder $query) => $query;
        }

        return $callback($this->query())
            ->get()
            ->map(static function (OssRepository $repository): OssRepository {
                if (is_string($repository->topics)) {
                    $repository->topics = json_decode($repository->topics, true);
                }

                return $repository;
            })
            ->toArray();
    }

    // /**
    //  * @return EloquentCollection<array-key, OssRepository>
    //  */
    // public function execute(Closure|null $callback = null): EloquentCollection
    // {
    //     if (!$callback) {
    //         $callback = fn (Builder $query) => $query;
    //     }

    //     return $this->query()->get();
    // }

    /**
     * @return Builder<OssRepository>
     */
    private function query(): Builder
    {
        return OssRepository::query()
            ->with([
                'latestRelease' => static function (HasOne $query): HasOne {
                    return $query
                        ->select([
                            'oss_releases.id',
                            'oss_releases.oss_repository_id',
                        ])
                        ->whereNotDraft()
                        ->whereNotPrerelease();
                },
            ])
            ->select([
                'id',
                'full_name',
                'description',
                'language',
                'languages',
                'topics',
                'archived',
                'license',
                'created_at',
            ])
            ->orderByDesc('created_at');
    }
}
