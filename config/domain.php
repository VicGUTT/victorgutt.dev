<?php

declare(strict_types=1);

$basePath = str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__) . '/appdomain');
$paths = collect(scandir($basePath) ?: [])
    ->filter(static fn (string $folder): bool => !in_array($folder, ['.', '..'], true))
    ->map(static fn (string $folder): string => "{$basePath}/{$folder}")
    ->values();

return [
    // 'base_path' => $basePath,
    'directories' => $paths->all(),
    'base_namespaces' => $paths
        ->map(static fn (string $path): string => str_replace($basePath, '', $path))
        ->map(static fn (string $path): string => 'Domain' . str_replace('/', '\\', $path))
        ->all(),
];
