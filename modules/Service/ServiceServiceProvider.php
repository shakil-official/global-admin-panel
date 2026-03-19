<?php

namespace Modules\Service;

use Illuminate\Support\ServiceProvider;
use Modules\Service\Repositories\Contracts\ServiceRepositoryInterface;
use Modules\Service\Repositories\Eloquent\ServiceRepository;
use Modules\Service\Services\Contracts\ServiceServiceInterface;
use Modules\Service\Services\ServiceService;

class ServiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(ServiceServiceInterface::class, ServiceService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Service');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}
