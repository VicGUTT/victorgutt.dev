<?php

declare(strict_types=1);

namespace Domain\Seo\Commands;

use Illuminate\Support\Uri;
use Illuminate\Console\Command;
use Spatie\Browsershot\Browsershot;
use Illuminate\Console\Prohibitable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

final class GenerateRouteOpenGraphImageCommand extends Command
{
    use Prohibitable;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'og:generate_route_image {--locale=} {--name=} {--params=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates an open graph image for a given route';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->isProhibited()) {
            return self::FAILURE;
        }

        /** @var string $routeLocale */
        $routeLocale = $this->option('locale');

        /** @var string $routeName */
        $routeName = $this->option('name');

        /** @var string $routeParams */
        $routeParams = $this->option('params');

        $imageName = md5("{$routeLocale}|{$routeName}|{$routeParams}") . '.jpg';

        $this->init($imageName);

        $this->components->info('Generating the `og:image`...');

        $this->generateImage($routeLocale, $routeName, $routeParams, $imageName);

        $this->components->info('Registering the `og:image`...');

        $this->registerImage($routeLocale, $routeName, $routeParams, $imageName);

        $path = str($imageName)->after(str_replace('\\', '/', base_path()));

        $this->components->info("Image ({$path}) generated successfully!");

        return self::SUCCESS;
    }

    /* Actions
    ------------------------------------------------*/

    private function init(string $imageName): void
    {
        File::ensureDirectoryExists(dirname($this->imageFullPath($imageName)));

        $storage = $this->storage();

        if ($storage->exists($this->manifestBasePath())) {
            return;
        }

        $storage->put($this->manifestBasePath(), '[]');
    }

    private function generateImage(string $routeLocale, string $routeName, string $routeParams, string $imageName): void
    {
        $params = json_decode($routeParams, true);

        $url = route('dev:og', [
            'locale' => $routeLocale,
            'route_name' => $routeName,
            ...$params,
        ]);

        $width = 1280;

        Browsershot::url($url)
            /**
             * 2:1 aspect ratio (based on Twitter "summary_large_image" image ratio).
             *
             * @see config/honeystone-seo.php
             */
            ->windowSize($width, $width / 2)
            ->waitForSelector('#icons-sprite')
            ->save($this->imageFullPath($imageName));
    }

    private function registerImage(string $routeLocale, string $routeName, string $routeParams, string $imageName): void
    {
        $storage = $this->storage();

        $params = json_decode($routeParams, true);
        $data = json_decode((string) $storage->get($this->manifestBasePath()), true);

        $imageFullPath = str($this->imageFullPath($imageName))->replace('\\', '/');
        $imageDigest = File::hash($imageFullPath);

        $pageFullUrl = route($routeName, ['locale' => $routeLocale, ...$params]);
        $pageRelativeUrl = (string) Uri::of($pageFullUrl)->path();

        $data[$routeLocale] = [
            ...($data[$routeLocale] ?? []),
            $routeName => [
                ...($data[$routeLocale][$routeName] ?? []),
                $pageRelativeUrl => [
                    ...$params,
                    'digest' => $imageDigest,
                    'path' => [
                        'full' => $imageFullPath,
                        'relative' => $imageName,
                    ],
                ],
            ],
        ];

        $storage->put(
            $this->manifestBasePath(),
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        );
    }

    /* Helpers
    ------------------------------------------------*/

    private function storage(): FilesystemAdapter
    {
        return Storage::disk('og_images');
    }

    private function manifestBasePath(): string
    {
        return '/manifest.json';
    }

    private function imageFullPath(string $imageName): string
    {
        return $this->storage()->path($imageName);
    }
}
