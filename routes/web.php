<?php

use Illuminate\Support\Facades\Route;


// Admin Routes
// Route::prefix('admin/cms')->group(function () {
//     Route::apiResource('pages', AdminPageController::class);
//     Route::post('{model}/{page_id}/sections', [AdminSectionController::class, 'store'])->name('sections.store');
//     Route::put('sections/{section}', [AdminSectionController::class, 'update'])->name('sections.update');
//     Route::delete('sections/{section}', [AdminSectionController::class, 'destroy'])->name('sections.destroy');
//     Route::patch('sections/group', [AdminSectionController::class, 'updateGroup'])->name('sections.group.update');
//     Route::apiResource('terms-and-conditions', AdminTermsAndConditionsController::class);
//     Route::apiResource('faqs', AdminFaqController::class);

// });

// // User Routes
// Route::prefix("/cms")->group(function () {
//     Route::apiResource('pages', PageController::class)->only(['index', 'show']);
//     Route::get('pages/{page_id}/{section_name}', [PageController::class, 'getPageSection']);
//     Route::get('/sections-names', [PageController::class, 'getSectionsNames']);
//     Route::apiResource('sections', SectionController::class)->only(['index', 'show']);

// });

