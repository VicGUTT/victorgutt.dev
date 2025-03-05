<?php

declare(strict_types=1);

namespace Domain\Resume\Commands;

use App\Support\GitHub\GitHub;
use Illuminate\Console\Command;
use Domain\Resume\Models\ResumeData;
use Illuminate\Console\Prohibitable;

final class HydrateResumeDataCommand extends Command
{
    use Prohibitable;

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'resume:hydrate_data';

    /**
     * The console command description.
     */
    protected $description = 'Fetches and saves the static resume data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->isProhibited()) {
            return self::FAILURE;
        }

        $data = GitHub::repo()->contentsAsFile('static_resume_data', '/data.json');
        $data = base64_decode($data->content, true);
        $data = json_decode($data, true);

        foreach ($data as $locale => $localizedData) {
            ResumeData::query()->updateOrCreate(['locale' => $locale], $localizedData);
        }

        $this->newLine(2);

        $this->components->info('Resume data hydrated successfully!');

        return self::SUCCESS;
    }
}
