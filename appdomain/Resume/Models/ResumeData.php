<?php

declare(strict_types=1);

namespace Domain\Resume\Models;

use Carbon\CarbonImmutable;
use Domain\Locale\Enums\LocaleEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 *
 * @property int $id
 * @property LocaleEnum $locale
 * @property array<array-key, mixed> $me
 * @property array<array-key, mixed> $experiences
 * @property array<array-key, mixed> $educations
 * @property array<array-key, mixed> $languages
 * @property array<array-key, mixed> $techs
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @method static Builder<static>|ResumeData newModelQuery()
 * @method static Builder<static>|ResumeData newQuery()
 * @method static Builder<static>|ResumeData query()
 * @method static Builder<static>|ResumeData whereLocale(\Domain\Locale\Enums\LocaleEnum|string $locale)
 * @mixin \Eloquent
 */
final class ResumeData extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'int',
        'locale' => LocaleEnum::class,
        'me' => 'json',
        'experiences' => 'json',
        'educations' => 'json',
        'languages' => 'json',
        'techs' => 'json',
    ];

    /* Scopes
    ------------------------------------------------*/

    /**
     * @param Builder<static> $query
     * @return Builder<static>
     */
    public function scopeWhereLocale(Builder $query, LocaleEnum|string $locale): Builder
    {
        if ($locale instanceof LocaleEnum) {
            $locale = $locale->value;
        }

        return $query->where('locale', $locale);
    }
}
