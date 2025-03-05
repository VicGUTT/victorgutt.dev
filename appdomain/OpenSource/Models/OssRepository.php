<?php

declare(strict_types=1);

namespace Domain\OpenSource\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Domain\OpenSource\Models\OssRelease;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Support\GitHub\Resources\License\LicenseData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\GitHub\Resources\User\Repos\UserRepoData;

/**
 *
 *
 * @property string $id
 * @property string $full_name
 * @property string|null $description
 * @property string|null $language
 * @property array<array-key, mixed>|null $languages
 * @property int $size
 * @property array<array-key, mixed>|null $topics
 * @property bool $archived
 * @property LicenseData|null $license
 * @property int $github_id
 * @property string $github_html_url
 * @property CarbonImmutable $pushed_at
 * @property CarbonImmutable $created_at
 * @property CarbonImmutable $updated_at
 * @property-read OssRelease|null $latestRelease
 * @property-read Collection<int, OssRelease> $releases
 * @property-read int|null $releases_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OssRepository newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OssRepository newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OssRepository query()
 * @mixin \Eloquent
 */
final class OssRepository extends Model
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
        'languages' => 'json',
        'topics' => 'json',
        'size' => 'int',
        'archived' => 'bool',
        'github_id' => 'int',
        'pushed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public static function fillFromUserRepoData(UserRepoData $data): static
    {
        return (new static())->fill([
            'id' => $data->name,
            'full_name' => $data->full_name,
            'description' => $data->description,
            'language' => $data->language,
            'size' => $data->size,
            'topics' => $data->topics,
            'archived' => $data->archived,
            'license' => $data->license,
            'github_id' => $data->id,
            'github_html_url' => $data->html_url,
            'pushed_at' => $data->pushed_at,
            'created_at' => $data->created_at,
            'updated_at' => $data->updated_at,
        ]);
    }

    public static function createFromUserRepoData(UserRepoData $data): static
    {
        return tap(static::fillFromUserRepoData($data), static fn (self $self): bool => $self->save());
    }

    public static function retrieveOrCreateFromUserRepoData(UserRepoData $data): static
    {
        $found = static::query()->find($data->name);

        if ($found) {
            return $found;
        }

        return static::createFromUserRepoData($data);
    }

    public static function createOrUpdateFromUserRepoData(UserRepoData $data): static
    {
        $self = static::retrieveOrCreateFromUserRepoData($data);

        if ($self->wasRecentlyCreated) {
            return $self;
        }

        return tap(static::fillFromUserRepoData($data), static function (self $new) use ($self): void {
            $self->update($new->getAttributes());
        });
    }

    /* Relationships
    ------------------------------------------------*/

    /**
     * @return HasMany<OssRelease, static>
     */
    public function releases(): HasMany
    {
        return $this->hasMany(OssRelease::class);
    }

    /**
     * @return HasOne<OssRelease, static>
     */
    public function latestRelease(): HasOne
    {
        return $this->releases()->one()->latestOfMany('created_at');
    }

    /* Accessors & Mutators
    ------------------------------------------------*/

    /**
     * @return Attribute<string|null, string|array|null>
     */
    protected function license(): Attribute
    {
        return Attribute::make(
            get: static fn (?string $value): ?LicenseData => $value ? LicenseData::from(json_decode($value, true)) : null,
            set: static fn (string|array|LicenseData|null $value): ?string => $value && !is_string($value) ? collect($value)->toJson() : $value,
        )->shouldCache();
    }
}
