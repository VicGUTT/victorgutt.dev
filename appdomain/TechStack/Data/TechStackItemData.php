<?php

declare(strict_types=1);

namespace Domain\TechStack\Data;

use App\Support\Data\Data;

final readonly class TechStackItemData extends Data
{
    public function __construct(
        public string $key,
        public string $label,
        public int|null $usage_start_year,
        public int|null $usage_end_year,
    ) {
    }
}
