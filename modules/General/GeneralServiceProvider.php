<?php

namespace Modules\General;

use Illuminate\Support\ServiceProvider;
use Modules\General\Repositories\Contracts\GeneralRepositoryInterface;
use Modules\General\Repositories\Eloquent\GeneralRepository;
use Modules\General\Services\Contracts\GeneralServiceInterface;
use Modules\General\Services\GeneralService;

class GeneralServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(GeneralRepositoryInterface::class, GeneralRepository::class);
        $this->app->bind(GeneralServiceInterface::class, GeneralService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'General');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}