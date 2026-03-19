<?php

namespace Modules\Coverage;

use Illuminate\Support\ServiceProvider;
use Modules\Coverage\Repositories\Contracts\CoverageRepositoryInterface;
use Modules\Coverage\Repositories\Eloquent\CoverageRepository;
use Modules\Coverage\Services\Contracts\CoverageServiceInterface;
use Modules\Coverage\Services\CoverageService;

class CoverageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CoverageRepositoryInterface::class, CoverageRepository::class);
        $this->app->bind(CoverageServiceInterface::class, CoverageService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Coverage');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}