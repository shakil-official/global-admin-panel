<?php

namespace Modules\Seo;

use Illuminate\Support\ServiceProvider;
use Modules\Seo\Repositories\Contracts\SeoRepositoryInterface;
use Modules\Seo\Repositories\Eloquent\SeoRepository;
use Modules\Seo\Services\Contracts\SeoServiceInterface;
use Modules\Seo\Services\SeoService;

class SeoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SeoRepositoryInterface::class, SeoRepository::class);
        $this->app->bind(SeoServiceInterface::class, SeoService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Seo');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}