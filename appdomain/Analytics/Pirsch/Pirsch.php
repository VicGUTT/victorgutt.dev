<?php

declare(strict_types=1);

namespace Domain\Analytics\Pirsch;

use Illuminate\Support\Fluent;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use App\Support\Resolvable\Contracts\Resolvable;
use App\Support\Resolvable\Concerns\CanBeResolved;

/**
 * @see https://github.com/pirsch-analytics/laravel-pirsch/blob/74659dc66cb6229ed3ffd7798cee5ab06d23d406/src/Pirsch.php
 * @see https://docs.pirsch.io/api-sdks/api#sending-page-views
 * @see https://github.com/pirsch-analytics/pirsch-php-sdk/blob/3233bd640fea5ad412d238cd1978e22f56fbc3d7/src/Client.php
 */
final class Pirsch implements Resolvable
{
    use CanBeResolved;

    private const API_BASE_URL = 'https://api.pirsch.io/api/v1/';
    private Fluent|null $jsData = null;

    public function enabled(): bool
    {
        if (blank(config('services.pirsch.token'))) {
            return false;
        }

        $ip = request()->ip();

        if (!$ip) {
            return true;
        }

        return !in_array($ip, config('services.pirsch.ignored_ips'), true);
    }

    public function trackHit(array $data = []): Response
    {
        return $this
            ->client()
            ->post('/hit', [
                ...$this->baseRequestData(),
                ...$this->extraRequestData(),
                ...$data,
                'tags' => [
                    ...$this->tagsRequestData(),
                    ...($data['tags'] ?? []),
                ],
            ]);
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(static::API_BASE_URL)
            ->withToken(config('services.pirsch.token'))
            ->timeout(5)
            ->retry(
                times: 3,
                sleepMilliseconds: 100,
                throw: true,
            )
            ->throw();
    }

    private function tagsRequestData(): array
    {
        $jsData = $this->jsData();

        return collect([
            'prefers_reduced_motion' => $jsData->prefers_reduced_motion,
            'prefers_reduced_data' => $jsData->prefers_reduced_data,
            'prefers_reduced_transparency' => $jsData->prefers_reduced_transparency,
            'prefers_contrast' => $jsData->prefers_contrast,
            'prefers_color_scheme' => $jsData->prefers_color_scheme,
            'timezone' => $jsData->timezone,
            'forced_colors' => $jsData->forced_colors,
        ])
        ->filter(static fn (mixed $value): bool => !is_null($value))
        ->map(static fn (int|bool|string $value): string => is_string($value) ? $value : json_encode($value))
        ->map(static fn (string $value): string => str($value)->limit(47)->value())
        ->all();
    }

    private function extraRequestData(): array
    {
        $jsData = $this->jsData();

        return array_filter([
            // 'title' => 'Page title (optional)',
            'screen_width' => $jsData->integer('screen_width'),
            'screen_height' => $jsData->integer('screen_height'),
        ]);
    }

    private function baseRequestData(): array
    {
        return [
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'accept_language' => request()->header('Accept-Language'),
            'sec_ch_ua' => request()->header('Sec-CH-UA'),
            'sec_ch_ua_mobile' => request()->header('Sec-CH-UA-Mobile'),
            'sec_ch_ua_platform' => request()->header('Sec-CH-UA-Platform'),
            'sec_ch_ua_platform_version' => request()->header('Sec-CH-UA-Platform-Version'),
            'sec_ch_width' => request()->header('Sec-CH-Width'),
            'sec_ch_viewport_width' => request()->header('Sec-CH-Viewport-Width'),
            'referrer' => request()->header('Referer'),
        ];
    }

    private function jsData(): Fluent
    {
        if ($this->jsData) {
            return $this->jsData;
        }

        $data = collect(request()->header())->reduce(static function (array $acc, array $value, string $key): array {
            if (!str_starts_with($key, 'x-visit-')) {
                return $acc;
            }

            $key = str($key)->after('x-visit-')->replace('-', '_')->value();
            $value = $value[0];

            // if (is_numeric($value)) {
            //     $value = (int) $value;
            // }

            $acc[$key] = $value;

            return $acc;
        }, []);

        $this->jsData = fluent($data);

        return $this->jsData;
    }
}
