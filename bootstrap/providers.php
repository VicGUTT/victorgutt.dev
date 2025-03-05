<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;
use App\Providers\DomainServiceProvider;

return [
    AppServiceProvider::class,
    RouteServiceProvider::class,
    DomainServiceProvider::class,
];
