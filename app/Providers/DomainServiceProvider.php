<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Application;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\SplFileInfo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;

final class DomainServiceProvider extends ServiceProvider
{
    /**
     * The application instance.
     *
     * @var Application
     */
    protected $app;

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->setupFactoryNamesGuessing();
        $this->setupFactoryModelNamesGuessing();
        $this->setupDomainMigrations();
        $this->setupDomainCommands();
    }

    private function setupFactoryNamesGuessing(): void
    {
        Factory::guessFactoryNamesUsing(static function (string $modelFQCN): string {
            /** @var class-string<Factory<Model>> $value */
            $value = str_replace('\\Models', '\\Database\\Factories', $modelFQCN) . 'Factory';

            return $value;
        });
    }

    private function setupFactoryModelNamesGuessing(): void
    {
        Factory::guessModelNamesUsing(static function (Factory $factory): string {
            /** @var class-string<Model> $value */
            $value = str_replace(['\\Database\\Factories', 'Factory'], ['\\Models', ''], $factory::class);

            return $value;
        });
    }

    private function setupDomainMigrations(): void
    {
        foreach (config('domain.directories') as $directory) {
            $this->loadMigrationsFrom("{$directory}/Database/Migrations");
        }
    }

    private function setupDomainCommands(): void
    {
        /** @var string[] $directories */
        $directories = config('domain.directories');

        $commands = collect($directories)
            ->filter(static fn (string $directory): bool => file_exists("{$directory}/Commands"))
            ->flatMap(static fn (string $directory): array => File::allFiles("{$directory}/Commands"))
            ->map(static function (SplFileInfo $file): string {
                return str($file->getRealPath())
                    ->replace(DIRECTORY_SEPARATOR, '/')
                    ->after(str_replace(DIRECTORY_SEPARATOR, '/', domain_path()))
                    ->prepend('Domain')
                    ->replace('/', '\\')
                    ->replace('.php', '')
                    ->value();
            })
            ->all();

        /** @var ConsoleKernel $kernel */
        $kernel = $this->app->get(ConsoleKernelContract::class);

        $kernel->addCommands($commands);
    }
}
