<?php

declare(strict_types=1);

namespace App\Console\Commands\Deploy;

use Illuminate\Console\Command;
use Domain\OpenSource\Commands\OssHydrateCommand;
use Domain\Resume\Commands\HydrateResumeDataCommand;
use Domain\Resume\Commands\GenerateResumePdfFilesCommand;

final class DeployActionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:actions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actions to be perfomed after deployment';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->call(OssHydrateCommand::class);
        $this->call(HydrateResumeDataCommand::class);
        $this->call(GenerateResumePdfFilesCommand::class);

        $this->newLine();

        $this->components->info('The actions have been performed successfully!');

        return self::SUCCESS;
    }
}
