<?php

declare(strict_types=1);

namespace App\Support\Helpers\Enums;

use App\Support\Helpers\Jobs\QueueableJob;

// art queue:listen --queue=critical,important,high,default,low
enum QueuePriorityEnum: string
{
    case CRITICAL = 'critical';
    case IMPORTANT = 'important';
    case HIGH = 'high';
    case DEFAULT = 'default';
    case LOW = 'low';

    public function forQueueableJob(QueueableJob $job): void
    {
        $job->onQueue($this->value);
    }
}
