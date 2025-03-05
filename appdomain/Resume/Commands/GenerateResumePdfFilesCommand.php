<?php

declare(strict_types=1);

namespace Domain\Resume\Commands;

use Illuminate\Console\Command;
use Domain\Locale\Enums\LocaleEnum;
use Illuminate\Console\Prohibitable;
use Domain\Resume\Actions\GenerateResumePdfFileAction;
use Domain\Resume\Actions\ShouldGenerateResumePdfFilesAction;

final class GenerateResumePdfFilesCommand extends Command
{
    use Prohibitable;

    /**
     * The name and signature of the console command.
     */
    protected $signature = 'resume:generate_pdfs';

    /**
     * The console command description.
     */
    protected $description = 'Renders the "Resume" page and generates PDF files for each supported languages and themes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->isProhibited()) {
            return self::FAILURE;
        }

        if (!ShouldGenerateResumePdfFilesAction::resolve()->execute()) {
            $this->components->info('Nothing to generate. Everything is up-to-date!');

            return self::SUCCESS;
        }

        $this->newLine();

        $bar = $this->output->createProgressBar(4);

        $bar->start();

        foreach (LocaleEnum::cases() as $locale) {
            foreach (['light', 'dark'] as $theme) {
                GenerateResumePdfFileAction::resolve()->execute($locale, $theme);

                $bar->advance();
            }
        }

        $bar->finish();

        $this->newLine(2);

        $this->components->info('Resume PDF files generated successfully!');

        return self::SUCCESS;
    }
}
