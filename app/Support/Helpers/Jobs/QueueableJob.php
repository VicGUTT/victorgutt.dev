<?php

declare(strict_types=1);

namespace App\Support\Helpers\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

abstract class QueueableJob implements ShouldQueue
{
    use Queueable;
}
