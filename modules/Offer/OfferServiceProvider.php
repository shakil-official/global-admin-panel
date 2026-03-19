<?php

namespace Modules\Offer;

use Illuminate\Support\ServiceProvider;
use Modules\Offer\Repositories\Contracts\OfferRepositoryInterface;
use Modules\Offer\Repositories\Eloquent\OfferRepository;
use Modules\Offer\Services\Contracts\OfferServiceInterface;
use Modules\Offer\Services\OfferService;

class OfferServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OfferRepositoryInterface::class, OfferRepository::class);
        $this->app->bind(OfferServiceInterface::class, OfferService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Offer');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}