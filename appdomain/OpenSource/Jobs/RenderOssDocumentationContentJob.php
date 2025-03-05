<?php

declare(strict_types=1);

namespace Domain\OpenSource\Jobs;

use Illuminate\Queue\InteractsWithQueue;
use App\Support\Helpers\Jobs\QueueableJob;
use Illuminate\Foundation\Bus\Dispatchable;
use Domain\OpenSource\Models\OssDocumentation;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\PendingDispatch;
use App\Support\Helpers\Enums\QueuePriorityEnum;
use Domain\OpenSource\Actions\RenderOssDocumentationContentAction;

final class RenderOssDocumentationContentJob extends QueueableJob implements ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    // use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $id)
    {
        QueuePriorityEnum::HIGH->forQueueableJob($this);
    }

    public static function dispatchForModel(OssDocumentation $doc): PendingDispatch
    {
        return static::dispatch($doc->id);
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return $this->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $doc = OssDocumentation::query()->findOrFail($this->id);
        $rendered = RenderOssDocumentationContentAction::resolve()->execute($doc);

        $doc->update([
            'rendered_content' => $rendered->content,
            'rendered_table_of_content' => $rendered->table_of_content,
        ]);
    }
}
