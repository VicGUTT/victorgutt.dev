<?php

declare(strict_types=1);

namespace App\Http\Controllers\Pages;

use App\Support\Pages\ProjectPage;

final class ProjectController
{
    public function __invoke(): ProjectPage
    {
        return ProjectPage::new([
            'sections' => __('pages/project.sections'),
        ]);
    }
}
