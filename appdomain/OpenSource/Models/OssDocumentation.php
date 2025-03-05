<?php

declare(strict_types=1);

namespace Domain\OpenSource\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\OpenSource\Models\OssRelease;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\GitHub\Resources\Repo\Contents\File\RepoContentFileData;

/**
 *
 *
 * @property string $id
 * @property string $name
 * @property string $path
 * @property string $sha
 * @property int $size
 * @property string|null $content
 * @property string|null $rendered_content
 * @property string|null $rendered_table_of_content
 * @property string $oss_release_id
 * @property string $github_html_url
 * @property-read OssRelease $release
 * @method static Builder<static>|OssDocumentation hasHtml()
 * @method static Builder<static>|OssDocumentation newModelQuery()
 * @method static Builder<static>|OssDocumentation newQuery()
 * @method static Builder<static>|OssDocumentation query()
 * @mixin \Eloquent
 */
final class OssDocumentation extends Model
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
        'size' => 'int',
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

    public static function fillFromRepoContentFileData(RepoContentFileData $data, OssRelease $release): static
    {
        return $release->documentations()->make([
            'id' => static::makeIdFromRepoContentFileData($data, $release),
            'name' => $data->name,
            'path' => $data->path,
            'sha' => $data->sha,
            'size' => $data->size,
            'content' => $data->content ? base64_decode($data->content, true) : null,
            'rendered_content' => null,
            'rendered_table_of_content' => null,
            'github_html_url' => $data->html_url,
        ]);
    }

    public static function createFromRepoContentFileData(RepoContentFileData $data, OssRelease $release): static
    {
        return tap(static::fillFromRepoContentFileData($data, $release), static fn (self $self): bool => $self->save());
    }

    public static function retrieveOrCreateFromRepoContentFileData(RepoContentFileData $data, OssRelease $release): static
    {
        $found = $release->documentations()->find(
            static::makeIdFromRepoContentFileData($data, $release),
        );

        if ($found) {
            return $found;
        }

        return static::createFromRepoContentFileData($data, $release);
    }

    public static function createOrUpdateFromRepoContentFileData(RepoContentFileData $data, OssRelease $release): static
    {
        $self = static::retrieveOrCreateFromRepoContentFileData($data, $release);

        if ($self->wasRecentlyCreated) {
            return $self;
        }

        return tap(static::fillFromRepoContentFileData($data, $release), static function (self $new) use ($self): void {
            $self->update($new->getAttributes());
        });
    }

    public static function makeIdFromRepoContentFileData(RepoContentFileData $data, OssRelease $release): string
    {
        return "{$release->id}/{$data->path}";
    }

    /* Relationships
    ------------------------------------------------*/

    public function release(): BelongsTo
    {
        return $this->belongsTo(OssRelease::class, 'oss_release_id');
    }

    /* Scopes
    ------------------------------------------------*/

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeHasHtml(Builder $query): Builder
    {
        return $query->whereNotNull('html');
    }
}
