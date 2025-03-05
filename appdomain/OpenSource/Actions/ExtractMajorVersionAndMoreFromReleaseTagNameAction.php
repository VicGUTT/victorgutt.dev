<?php

declare(strict_types=1);

namespace Domain\OpenSource\Actions;

use App\Support\Action\Action;

final readonly class ExtractMajorVersionAndMoreFromReleaseTagNameAction extends Action
{
    public function execute(string $tagName): string
    {
        // Ex.: `2.7.4` -> `2.x` | `v2.7.4` -> `2.x`
        return str($tagName)->lower()->trim('v')->before('.')->append('.x')->value();
    }
}
