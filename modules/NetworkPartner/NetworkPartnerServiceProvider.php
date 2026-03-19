<?php

namespace Modules\NetworkPartner;

use Illuminate\Support\ServiceProvider;
use Modules\NetworkPartner\Repositories\Contracts\NetworkPartnerRepositoryInterface;
use Modules\NetworkPartner\Repositories\Eloquent\NetworkPartnerRepository;
use Modules\NetworkPartner\Services\Contracts\NetworkPartnerServiceInterface;
use Modules\NetworkPartner\Services\NetworkPartnerService;

class NetworkPartnerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(NetworkPartnerRepositoryInterface::class, NetworkPartnerRepository::class);
        $this->app->bind(NetworkPartnerServiceInterface::class, NetworkPartnerService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'NetworkPartner');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}