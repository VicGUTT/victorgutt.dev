<?php

declare(strict_types=1);

namespace Domain\TechStack\Controllers;

use Domain\TechStack\TechStack;
use App\Support\Pages\TechStackPage;

final class TechStackController
{
    public function __invoke(): TechStackPage
    {
        return TechStackPage::new([
            'sections' => TechStack::resolve()->toArray(),
        ]);
    }
}
