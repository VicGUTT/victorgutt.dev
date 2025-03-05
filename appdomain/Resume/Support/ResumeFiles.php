<?php

declare(strict_types=1);

namespace Domain\Resume\Support;

use Domain\Locale\Enums\LocaleEnum;
use Domain\Resume\Models\ResumeData;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Support\Resolvable\Contracts\Resolvable;
use App\Support\Resolvable\Concerns\CanBeResolved;

final class ResumeFiles implements Resolvable
{
    use CanBeResolved;

    /**
     * @return array {
     *    data: string;
     *    build: string;
     * }
     */
    public function digests(): array
    {
        return [
            'data' => hash('xxh128', ResumeData::query()->pluck('updated_at')->toJson()),
            'build' => hash_file('xxh128', public_path('build/manifest.json')),
        ];
    }

    /**
     * @return array {
     *    digests?: {
     *        data: string,
     *        build: string,
     *    },
     *    files?: array<string, array<string, {
     *        name: string,
     *        digest: string,
     *    }>>,
     * }
     */
    public function manifest(): array
    {
        $manifestPath = $this->basePath('manifest.json');

        if (!File::exists($manifestPath)) {
            return [];
        }

        return json_decode(File::get($manifestPath), true);
    }

    public function path(LocaleEnum $locale, string $theme): string
    {
        return $this->basePath($this->makeResumeFileName($locale, $theme));
    }

    public function register(LocaleEnum $locale, string $theme): void
    {
        $data = [
            'digests' => $this->digests(),
        ];

        $manifestPath = $this->basePath('manifest.json');

        if (File::exists($manifestPath)) {
            $data['files'] = json_decode(File::get($manifestPath), true)['files'];
        }

        $resumeFileName = $this->makeResumeFileName($locale, $theme);
        $resumePath = $this->path($locale, $theme);

        $resumeDigest = hash_file('xxh128', $resumePath);

        $data['files'][$locale->value][$theme] = [
            'name' => $resumeFileName,
            'digest' => $resumeDigest,
        ];

        File::put($manifestPath, json_encode($data, JSON_UNESCAPED_SLASHES));
    }

    /* Helpers
    ------------------------------------------------*/

    private function basePath(string $path = ''): string
    {
        return Storage::disk('resume_pdfs')->path("/{$path}");
    }

    private function makeResumeFileName(LocaleEnum $locale, string $theme): string
    {
        $localizedTheme = mb_strtolower(__("words.{$theme}", locale: $locale->value));
        $suffix = mb_strtolower(__('words.resume', locale: $locale->value));

        $name = str(config('app.name'))->snake();

        return "{$name}_{$suffix}_{$localizedTheme}.pdf";
    }
}
