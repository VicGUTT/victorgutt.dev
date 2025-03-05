<?php

declare(strict_types=1);

namespace Domain\OpenSource\Data;

use App\Support\Data\Data;

final readonly class RenderedOssDocumentationContentData extends Data
{
    public function __construct(
        public string $content,
        public string $table_of_content,
    ) {
    }
}
