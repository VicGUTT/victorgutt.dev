<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pages\HomeController;
use Domain\Analytics\Middlewares\TrackPageview;
use Domain\Resume\Controllers\ResumeController;
use App\Http\Controllers\Pages\ContactController;
use App\Http\Controllers\Pages\ProjectController;
use Domain\Analytics\Controllers\NojsVisitController;
use Domain\TechStack\Controllers\TechStackController;
use Domain\Analytics\Controllers\InitialVisitController;
use Domain\Locale\Support\LocaleAwareRouteFallbackHandler;
use Domain\OpenSource\Controllers\OssRepositoryShowController;
use Domain\OpenSource\Controllers\OssRepositoryIndexController;

Route::get('/', HomeController::class)->name('home');
Route::get('/projects', ProjectController::class)->name('projects');
Route::get('/open-source', OssRepositoryIndexController::class)->name('open_source.index');
Route::get('/open-source/{path}', OssRepositoryShowController::class)->name('open_source.show');
Route::get('/tech-stack', TechStackController::class)->name('tech_stack');
Route::get('/contact', ContactController::class)->name('contact');

Route::get('/resume', ResumeController::class)->name('resume.en');
Route::get('/cv', ResumeController::class)->name('resume.fr');

Route::withoutMiddleware(TrackPageview::class)->prefix('/visit')->name('visit.')->group(static function (): void {
    Route::post('/initial', InitialVisitController::class)->name('initial');
    Route::get('/nojs/{token}', NojsVisitController::class)->name('nojs')->middleware('signed');
});

Route::fallback((new LocaleAwareRouteFallbackHandler())(...))->name('locale_aware_fallback');
