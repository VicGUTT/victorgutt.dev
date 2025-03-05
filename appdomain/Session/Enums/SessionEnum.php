<?php

declare(strict_types=1);

namespace Domain\Session\Enums;

use VicGutt\PhpEnhancedEnum\Concerns\Enumerable;
use VicGutt\PhpEnhancedEnum\Contracts\EnumerableContract;

enum SessionEnum: string implements EnumerableContract
{
    use Enumerable;

    case LOCALE = 'locale';
}
