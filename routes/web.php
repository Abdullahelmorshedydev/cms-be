<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\Cms\PageController as CmsPageController;
use App\Http\Controllers\Dashboard\Cms\SectionController as CmsSectionController;
use App\Http\Controllers\Dashboard\Cms\SectionTypeController as CmsSectionTypeController;

use App\Http\Controllers\Dashboard\RoleController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\AuthController;
use App\Http\Controllers\Dashboard\BlogController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\FormController;
use App\Http\Controllers\Dashboard\FormEmailController;

// Dashboard Routes
Route::prefix('/dashboard')->name('dashboard.')->middleware(['web'])->group(function () {
    // Guest routes (login, password reset) - must be accessible without auth
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post'); // 5 attempts per minute
        Route::get('/forget-password', [AuthController::class, 'forgetPassword'])->name('password.request');
        Route::post('/reset-code', [AuthController::class, 'sendResetCode'])->middleware('throttle:3,1')->name('password.email'); // 3 per minute
        Route::get('/reset-code/{email}', [AuthController::class, 'resetCodePage'])->name('password.reset.code');
        Route::post('/verify-code', [AuthController::class, 'verifyCode'])->middleware('throttle:5,1')->name('password.verify'); // 5 per minute
        Route::get('/reset-password/{email}', [AuthController::class, 'resetPasswordPage'])->name('password.reset');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update'); // 5 per minute
    });

    // Protected routes (require authentication)
    Route::middleware(['auth'])->group(function () {
        // Dashboard
        Route::get('/home', [DashboardController::class, 'index'])->name('home');

        // Auth Routes
        Route::controller(AuthController::class)->group(function () {
            // Logout
            Route::post('auth/logout', 'logout')->name('logout');

            // Profile
            Route::prefix('/profile')->as('profile.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::put('/', 'update')->name('update');
                Route::put('/change-password', 'changePassword')->name('change_password');
            });
        });

        // Roles
        Route::controller(RoleController::class)->prefix('/roles')->as('roles.')->group(function () {
            Route::get('/', 'index')->middleware('check.permission:role.show')->name('index');
            Route::middleware('check.permission:role.create')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });
            Route::middleware('check.permission:role.edit')->group(function () {
                Route::get('/{role}/edit', 'edit')->name('edit');
                Route::put('/{role}', 'update')->name('update');
            });
            Route::middleware('check.permission:role.delete')->group(function () {
                Route::delete('/destroy', 'destroyAll')->name('delete');
                Route::delete('/{role}', 'destroy')->name('destroy');
            });
        });

        // Users
        Route::controller(UserController::class)->prefix('/users')->as('users.')->group(function () {
            Route::get('/', 'index')->middleware('check.permission:user.show')->name('index');
            Route::middleware('check.permission:user.create')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });
            Route::middleware('check.permission:user.edit')->group(function () {
                Route::get('/{user}/edit', 'edit')->name('edit');
                Route::put('/{user}', 'update')->name('update');
            });
            Route::middleware('check.permission:user.delete')->group(function () {
                Route::delete('/delete', 'destroyAll')->name('delete');
                Route::delete('/{user}', 'destroy')->name('destroy');
            });
            Route::post('/import', 'import')->middleware('check.permission:user.import')->name('import');
            Route::get('/export', 'export')->middleware('check.permission:user.export')->name('export');
        });

        // Settings
        Route::controller(SettingController::class)->prefix('/settings')->as('settings.')->group(function () {
            Route::middleware('check.permission:settings.show')->get('/', 'index')->name('index');
            Route::middleware('check.permission:settings.edit')->post('/{setting}', 'update')->name('update');
        });

        // Blogs
        Route::controller(BlogController::class)->prefix('/blogs')->as('blogs.')->group(function () {
            // Static routes first (must come before parameter routes to avoid conflicts)
            Route::get('/', 'index')->middleware('check.permission:blog.show')->name('index');
            Route::post('/import', 'import')->middleware('check.permission:blog.import')->name('import');
            Route::get('/export', 'export')->middleware('check.permission:blog.export')->name('export');

            // Blog Comments (static routes)
            Route::post('/comments', 'storeComment')->middleware('check.permission:blog.comment.create')->name('comments.store');

            Route::middleware('check.permission:blog.create')->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });
            Route::middleware('check.permission:blog.delete')->group(function () {
                Route::delete('/delete', 'destroyAll')->name('delete');
            });

            // Parameter routes (must come last)
            Route::get('/{blog}/comments', 'getComments')->middleware('check.permission:blog.show')->name('comments.index');
            Route::put('/comments/{comment}', 'updateComment')->middleware('check.permission:blog.comment.edit')->name('comments.update');
            Route::post('/comments/{comment}/approve', 'approveComment')->middleware('check.permission:blog.comment.approve')->name('comments.approve');
            Route::post('/comments/{comment}/reject', 'rejectComment')->middleware('check.permission:blog.comment.reject')->name('comments.reject');
            Route::delete('/comments/{comment}', 'destroyComment')->middleware('check.permission:blog.comment.delete')->name('comments.destroy');
            Route::middleware('check.permission:blog.edit')->group(function () {
                Route::get('/{blog}/edit', 'edit')->name('edit');
                Route::put('/{blog}', 'update')->name('update');
            });
            Route::middleware('check.permission:blog.delete')->group(function () {
                Route::delete('/{blog}', 'destroy')->name('destroy');
            });
            Route::get('/{blog}', 'show')->middleware('check.permission:blog.show')->name('show');
        });

        // Forms - All Types
        Route::controller(FormController::class)->prefix('/forms')->as('forms.')->group(function () {
            // Static routes first (most specific)
            Route::get('/', 'index')->middleware('check.permission:form.show')->name('index');
            Route::get('/export/csv', 'export')->middleware('check.permission:form.export')->name('export');
            Route::post('/bulk-delete', 'bulkDelete')->middleware('check.permission:form.delete')->name('bulk-delete');
            Route::post('/bulk-mark-as-read', 'bulkMarkAsRead')->middleware('check.permission:form.edit')->name('bulk-mark-as-read');

            // Dynamic slug routes for specific form types (must come before {form} parameter routes)
            foreach (\App\Enums\FormTypeEnum::cases() as $formType) {
                Route::get('/' . $formType->slug(), function (\Illuminate\Http\Request $request) use ($formType) {
                    return app(FormController::class)->index($request, $formType->value);
                })->middleware('check.permission:' . $formType->permission())
                    ->name($formType->slug());
            }

            // Generic routes with parameters (must come last to avoid conflicts)
            Route::get('/type/{type}', 'index')->middleware('check.permission:form.show')->name('by-type');
            Route::get('/{form}', 'show')->middleware('check.permission:form.show')->name('show');
            Route::post('/{form}/mark-as-read', 'markAsRead')->middleware('check.permission:form.edit')->name('mark-as-read');
            Route::post('/{form}/mark-as-unread', 'markAsUnread')->middleware('check.permission:form.edit')->name('mark-as-unread');
            Route::delete('/{form}', 'destroy')->middleware('check.permission:form.delete')->name('destroy');
        });

        // Form Emails (Recipients Management)
        Route::controller(FormEmailController::class)->prefix('/form-emails')->as('form-emails.')->group(function () {
            Route::get('/', 'index')->middleware('check.permission:form-email.show')->name('index');
            Route::get('/create', 'create')->middleware('check.permission:form-email.create')->name('create');
            Route::post('/', 'store')->middleware('check.permission:form-email.create')->name('store');
            Route::get('/{form_email}/edit', 'edit')->middleware('check.permission:form-email.edit')->name('edit');
            Route::put('/{form_email}', 'update')->middleware('check.permission:form-email.edit')->name('update');
            Route::delete('/{form_email}', 'destroy')->middleware('check.permission:form-email.delete')->name('destroy');
        });

        // CMS (Pages, Section Types, Sections)
        Route::prefix('/cms')->as('cms.')->group(function () {
            // Pages
            Route::controller(CmsPageController::class)->prefix('/pages')->as('pages.')->group(function () {
                Route::get('/', 'index')->middleware('check.permission:page.show')->name('index');
                Route::get('/create', 'create')->middleware('check.permission:page.create')->name('create');
                Route::post('/', 'store')->middleware('check.permission:page.create')->name('store');
                Route::get('/{page}', 'show')->middleware('check.permission:page.show')->name('show');
                Route::get('/{page}/edit', 'edit')->middleware('check.permission:page.edit')->name('edit');
                Route::put('/{page}', 'update')->middleware('check.permission:page.edit')->name('update');
                Route::delete('/{page}', 'destroy')->middleware('check.permission:page.delete')->name('destroy');
            });

            // Section Types
            Route::controller(CmsSectionTypeController::class)->prefix('/section-types')->as('section-types.')->group(function () {
                Route::get('/', 'index')->middleware('check.permission:section-type.show')->name('index');
                Route::get('/create', 'create')->middleware('check.permission:section-type.create')->name('create');
                Route::post('/', 'store')->middleware('check.permission:section-type.create')->name('store');
                Route::get('/{section_type}', 'show')->middleware('check.permission:section-type.show')->name('show');
                Route::get('/{section_type}/edit', 'edit')->middleware('check.permission:section-type.edit')->name('edit');
                Route::put('/{section_type}', 'update')->middleware('check.permission:section-type.edit')->name('update');
                Route::delete('/{section_type}', 'destroy')->middleware('check.permission:section-type.delete')->name('destroy');
            });

            // Sections
            Route::controller(CmsSectionController::class)->prefix('/sections')->as('sections.')->group(function () {
                Route::get('/', 'index')->middleware('check.permission:section.show')->name('index');
                Route::get('/create', 'create')->middleware('check.permission:section.create')->name('create');
                Route::post('/', 'store')->middleware('check.permission:section.create')->name('store');
                Route::get('/{section}', 'show')->middleware('check.permission:section.show')->name('show');
                Route::get('/{section}/edit', 'edit')->middleware('check.permission:section.edit')->name('edit');
                Route::put('/{section}', 'update')->middleware('check.permission:section.edit')->name('update');
                Route::delete('/{section}', 'destroy')->middleware('check.permission:section.delete')->name('destroy');

                // Group update for ordered sections
                Route::post('/group', 'updateGroup')->middleware('check.permission:section.edit')->name('group.update');

                // Store section for specific model/page (reuses same store logic)
                Route::post('/{model}/{page_id}', 'store')->middleware('check.permission:section.create')->name('store.for_page');
            });
        });
    });
});

