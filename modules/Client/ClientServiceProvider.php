<?php

namespace Modules\Client;

use Illuminate\Support\ServiceProvider;
use Modules\Client\Repositories\Contracts\ClientRepositoryInterface;
use Modules\Client\Repositories\Eloquent\ClientRepository;
use Modules\Client\Services\Contracts\ClientServiceInterface;
use Modules\Client\Services\ClientService;

class ClientServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientRepositoryInterface::class, ClientRepository::class);
        $this->app->bind(ClientServiceInterface::class, ClientService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Client');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}
