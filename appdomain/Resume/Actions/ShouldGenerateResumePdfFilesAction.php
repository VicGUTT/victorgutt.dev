<?php

declare(strict_types=1);

namespace Domain\Resume\Actions;

use App\Support\Action\Action;
use Domain\Resume\Support\ResumeFiles;

final readonly class ShouldGenerateResumePdfFilesAction extends Action
{
    public function execute(): bool
    {
        $resumeFiles = ResumeFiles::resolve();

        $manifest = $resumeFiles->manifest();

        if (empty($manifest['files'] ?? null)) {
            return true;
        }

        $digests = $resumeFiles->digests();

        if ($digests['data'] !== $manifest['digests']['data']) {
            return true;
        }

        if ($digests['build'] !== $manifest['digests']['build']) {
            return true;
        }

        return false;
    }
}
