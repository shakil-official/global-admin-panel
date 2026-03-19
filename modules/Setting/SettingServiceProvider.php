<?php

namespace Modules\Setting;

use Illuminate\Support\ServiceProvider;
use Modules\Setting\Repositories\Contracts\SettingRepositoryInterface;
use Modules\Setting\Repositories\Eloquent\SettingRepository;
use Modules\Setting\Services\Contracts\SettingServiceInterface;
use Modules\Setting\Services\SettingService;

class SettingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SettingRepositoryInterface::class, SettingRepository::class);
        $this->app->bind(SettingServiceInterface::class, SettingService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Setting');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}
