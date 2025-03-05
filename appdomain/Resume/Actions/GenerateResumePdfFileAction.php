<?php

declare(strict_types=1);

namespace Domain\Resume\Actions;

use App\Support\Action\Action;
use Domain\Locale\Enums\LocaleEnum;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\File;
use Domain\Resume\Support\ResumeFiles;

final readonly class GenerateResumePdfFileAction extends Action
{
    public function execute(LocaleEnum $locale, string $theme): void
    {
        $resumeFiles = ResumeFiles::resolve();

        $path = $resumeFiles->path($locale, $theme);

        File::ensureDirectoryExists(dirname($path));

        $url = route("web:resume.{$locale->value}", [
            'locale' => $locale->value,
            'theme' => $theme,
            'printing' => true,
        ]);

        Browsershot::url($url)
            ->format('A4')
            ->scale(0.7)
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->savePdf($path);

        $resumeFiles->register($locale, $theme);
    }
}
