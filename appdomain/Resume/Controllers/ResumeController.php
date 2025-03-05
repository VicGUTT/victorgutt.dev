<?php

declare(strict_types=1);

namespace Domain\Resume\Controllers;

use App\Support\Pages\ResumePage;
use Domain\Resume\Models\ResumeData;
use Domain\Resume\Support\ResumeFiles;
use Illuminate\Support\Facades\Storage;

final class ResumeController
{
    public function __invoke(string $locale): ResumePage
    {
        $data = ResumeData::query()->whereLocale($locale)->firstOrFail();

        /** @var array<string, array<string, string>> $files */
        $files = ResumeFiles::resolve()->manifest()['files'][$locale] ?? [];
        $files = collect($files)->map(static function (array $file): string {
            return Storage::disk('resume_pdfs')->temporaryUrl(
                $file['name'],
                now()->addWeek(),
            );
        });

        return ResumePage::new([
            ...$data->toArray(),
            'files' => $files->all(),
        ]);
    }
}
