<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('media', function ($app) {
            return new \App\Services\MediaService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load global helper functions (e.g., setting(), isActiveRoute(), etc.)
        $helpersPath = app_path('Helpers/Helpers.php');
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }

        Schema::defaultStringLength(191);
    }
}
