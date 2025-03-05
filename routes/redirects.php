<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/resume', '/en/resume')->name('resume.without_locale.fr');
Route::permanentRedirect('/cv', '/fr/cv')->name('resume.without_locale.en');

Route::permanentRedirect('/en/cv', '/en/resume')->name('resume.with_locale.en');
Route::permanentRedirect('/fr/resume', '/fr/cv')->name('resume.with_locale.fr');
