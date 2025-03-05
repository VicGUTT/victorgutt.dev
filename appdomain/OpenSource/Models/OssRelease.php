<?php

declare(strict_types=1);

namespace Domain\OpenSource\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Domain\OpenSource\Models\OssRepository;
use Illuminate\Database\Eloquent\Collection;
use Domain\OpenSource\Models\OssDocumentation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\GitHub\Resources\Repo\Releases\RepoReleaseData;
use Domain\OpenSource\Actions\ExtractMajorVersionAndMoreFromReleaseTagNameAction;

/**
 *
 *
 * @property string $id
 * @property string $tag_name
 * @property string $name
 * @property string $major_version_and_more
 * @property string|null $body
 * @property bool $draft
 * @property bool $prerelease
 * @property string $oss_repository_id
 * @property int $github_id
 * @property string $github_html_url
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $published_at
 * @property-read Collection<int, OssDocumentation> $documentations
 * @property-read int|null $documentations_count
 * @property-read OssRepository $repository
 * @method static Builder<static>|OssRelease newModelQuery()
 * @method static Builder<static>|OssRelease newQuery()
 * @method static Builder<static>|OssRelease query()
 * @method static Builder<static>|OssRelease whereDraft()
 * @method static Builder<static>|OssRelease whereNotDraft()
 * @method static Builder<static>|OssRelease whereNotPrerelease()
 * @method static Builder<static>|OssRelease wherePrerelease()
 * @mixin \Eloquent
 */
final class OssRelease extends Model
{
    use HasFactory;

    /**
     * The "type" of the primary key ID.
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'draft' => 'bool',
        'prerelease' => 'bool',
        'github_id' => 'int',
        'created_at' => 'datetime',
        'published_at' => 'datetime',
    ];

    /**
     * The name of the "created at" column.
     *
     * @var string|null
     */
    public const CREATED_AT = null;

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    public const UPDATED_AT = null;

    /* Static methods
    ------------------------------------------------*/

    public static function fillFromRepoReleaseData(RepoReleaseData $data, OssRepository $repository): static
    {
        $majorVersionAndMore = ExtractMajorVersionAndMoreFromReleaseTagNameAction::resolve()->execute($data->tag_name);

        return $repository->releases()->make([
            'id' => static::makeIdFromRepoReleaseData($data, $repository),
            'tag_name' => $data->tag_name,
            'name' => $data->name,
            'major_version_and_more' => $majorVersionAndMore,
            'body' => $data->body,
            'draft' => $data->draft,
            'prerelease' => $data->prerelease,
            'github_id' => $data->id,
            'github_html_url' => $data->html_url,
            'created_at' => $data->created_at,
            'published_at' => $data->published_at,
        ]);
    }

    public static function createFromRepoReleaseData(RepoReleaseData $data, OssRepository $repository): static
    {
        return tap(static::fillFromRepoReleaseData($data, $repository), static fn (self $self): bool => $self->save());
    }

    public static function retrieveOrCreateFromRepoReleaseData(RepoReleaseData $data, OssRepository $repository): static
    {
        $found = $repository->releases()->find(
            static::makeIdFromRepoReleaseData($data, $repository),
        );

        if ($found) {
            return $found;
        }

        return static::createFromRepoReleaseData($data, $repository);
    }

    public static function createOrUpdateFromRepoReleaseData(RepoReleaseData $data, OssRepository $repository): static
    {
        $self = static::retrieveOrCreateFromRepoReleaseData($data, $repository);

        if ($self->wasRecentlyCreated) {
            return $self;
        }

        return tap(static::fillFromRepoReleaseData($data, $repository), static function (self $new) use ($self): void {
            $self->update($new->getAttributes());
        });
    }

    public static function makeIdFromRepoReleaseData(RepoReleaseData $data, OssRepository $repository): string
    {
        $majorVersionAndMore = ExtractMajorVersionAndMoreFromReleaseTagNameAction::resolve()->execute($data->tag_name);

        return "{$repository->id}/{$majorVersionAndMore}";
    }

    /* Relationships
    ------------------------------------------------*/

    public function repository(): BelongsTo
    {
        return $this->belongsTo(OssRepository::class, 'oss_repository_id');
    }

    /**
     * @return HasMany<OssDocumentation, static>
     */
    public function documentations(): HasMany
    {
        return $this->hasMany(OssDocumentation::class);
    }

    /* Scopes
    ------------------------------------------------*/

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeWhereDraft(Builder $query): Builder
    {
        return $query->where('draft', true);
    }

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeWhereNotDraft(Builder $query): Builder
    {
        return $query->where('draft', false);
    }

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeWherePrerelease(Builder $query): Builder
    {
        return $query->where('prerelease', true);
    }

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeWhereNotPrerelease(Builder $query): Builder
    {
        return $query->where('prerelease', false);
    }
}
