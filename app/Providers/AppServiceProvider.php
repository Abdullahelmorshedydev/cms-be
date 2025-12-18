<?php

namespace App\Providers;

use App\Broadcasting\SmsChannel;
use App\HelperClasses\ClientConfig;
use App\HelperClasses\Settings;
use App\Helpers\HttpRequestHelper;
use App\Helpers\MediaHelper;
use App\Helpers\ClientHelper;
use App\Helpers\GeneralHelper;
use App\Helpers\VerifyHelper;
use Modules\Notification\Services\SmsService;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Modules\User\Services\Strategies\Crm\KeyloopStrategy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('media', fn() => new MediaHelper());
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
