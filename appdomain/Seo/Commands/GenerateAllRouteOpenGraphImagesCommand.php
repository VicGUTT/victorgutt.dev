<?php

declare(strict_types=1);

namespace Domain\Seo\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Domain\Locale\Enums\LocaleEnum;
use Illuminate\Console\Prohibitable;
use Illuminate\Routing\Route as Route;
use Domain\OpenSource\Models\OssRelease;
use Illuminate\Support\Facades\Route as RouteFacade;
use Domain\Seo\Commands\GenerateRouteOpenGraphImageCommand;

final class GenerateAllRouteOpenGraphImagesCommand extends Command
{
    use Prohibitable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'og:generate_all_route_images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates an open graph image for all applicable routes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->isProhibited()) {
            return self::FAILURE;
        }

        $this->newLine();

        $locales = $this->getLocales();
        $routeNames = $this->getRouteNames();
        $generationCommandOptions = $this->makeGenerationCommandOptions($locales, $routeNames);

        $bar = $this->output->createProgressBar($generationCommandOptions->count());

        $bar->start();

        $generationCommandOptions->each(function (array $options) use ($bar): void {
            $this->callSilently(GenerateRouteOpenGraphImageCommand::class, [
                '--locale' => $options['locale'],
                '--name' => $options['name'],
                '--params' => json_encode($options['params']),
            ]);

            $bar->advance();
        });

        $bar->finish();

        $this->newLine(2);

        $this->components->info('Images generated successfully!');

        return self::SUCCESS;
    }

    /* Actions
    ------------------------------------------------*/

    /**
     * @param Collection<int, LocaleEnum> $locales
     * @param Collection<int, string> $routeNames
     * @return Collection<int, array{
     *    locale: string,
     *    name: string,
     *    params: array,
     * }>
     */
    private function makeGenerationCommandOptions(Collection $locales, Collection $routeNames): Collection
    {
        return $locales->reduce(static function (Collection $acc, LocaleEnum $locale) use ($routeNames): Collection {
            $routeNames->each(static function (string $routeName) use ($locale, $acc): Collection {
                $options = [
                    'locale' => $locale->value,
                    'name' => $routeName,
                    'params' => [],
                ];

                if ($routeName !== 'web:open_source.show') {
                    $acc[] = $options;

                    return $acc;
                }

                OssRelease::query()
                    ->select('id')
                    ->orderByDesc('created_at')
                    ->get()
                    ->each(static function (OssRelease $release) use ($acc, $options): Collection {
                        $options['params'] = [
                            'path' => $release->id,
                        ];

                        $acc[] = $options;

                        return $acc;
                    });

                return $acc;
            });

            return $acc;
        }, collect());
    }

    /* Helpers
    ------------------------------------------------*/

    /**
     * @return Collection<int, LocaleEnum>
     */
    private function getLocales(): Collection
    {
        return collect(LocaleEnum::cases());
    }

    /**
     * @return Collection<int, string>
     */
    private function getRouteNames(): Collection
    {
        return collect(RouteFacade::getRoutes()->getRoutesByName())
            ->filter(static function (Route $route, string $routeName): bool {
                if (!str_starts_with($routeName, 'web:')) {
                    return false;
                }

                if ($routeName === 'web:locale_aware_fallback') {
                    return false;
                }

                if (str_starts_with($routeName, 'web:visit')) {
                    return false;
                }

                if (str_starts_with($routeName, 'web:resume')) {
                    return false;
                }

                return true;
            })
            ->keys();
    }
}
