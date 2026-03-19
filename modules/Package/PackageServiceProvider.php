<?php

namespace Modules\Package;

use Illuminate\Support\ServiceProvider;
use Modules\Package\Repositories\Contracts\PackageRepositoryInterface;
use Modules\Package\Repositories\Eloquent\PackageRepository;
use Modules\Package\Services\Contracts\PackageServiceInterface;
use Modules\Package\Services\PackageService;

class PackageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PackageRepositoryInterface::class, PackageRepository::class);
        $this->app->bind(PackageServiceInterface::class, PackageService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Package');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}