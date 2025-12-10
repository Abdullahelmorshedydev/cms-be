<?php

use App\Http\Controllers\Admin\SectionController as AdminSectionController;
use App\Http\Controllers\Admin\SectionTypeController;
use App\Http\Middleware\APILocaleMiddleware;
use App\Http\Controllers\API\PageController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\API\SectionController as SectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Admin Routes
Route::prefix('cms/admin')->group(function () {
    Route::apiResource('pages', AdminPageController::class);
    Route::apiResource('section-types', SectionTypeController::class);
    Route::apiResource('sections', AdminSectionController::class);
    Route::post('{model}/{page_id}/sections', [AdminSectionController::class, 'store'])->name('sections.store');
    Route::post('sections/group', [AdminSectionController::class, 'updateGroup'])->name('sections.group.update');
});

// User Routes
Route::prefix("/cms")->middleware(APILocaleMiddleware::class)->group(
    function () {
        Route::get('sections/types', [SectionController::class, 'getSectionTypes'])->name('sections.types.get');
        Route::apiResource('pages', PageController::class)->only(['index', 'show']);
        Route::get('pages/{page_slug}/{section_name}', [PageController::class, 'getPageSection']);
        Route::get('/sections-names', [PageController::class, 'getSectionsNames']);
        Route::apiResource('sections', SectionController::class)->only(['index', 'show']);
    }
);
