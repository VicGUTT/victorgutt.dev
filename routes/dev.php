<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Domain\Seo\Controllers\Dev\OpenGraphController;
use Domain\Locale\Middlewares\EnsureLocaleIsUpToDate;

Route::inertia('/colors', '_dev/colors')->name('colors');

Route::get('/og/{locale}/{route_name}', OpenGraphController::class)->middleware(EnsureLocaleIsUpToDate::class)->name('og');
