<?php

namespace Modules\Paybill;

use Illuminate\Support\ServiceProvider;
use Modules\Paybill\Repositories\Contracts\PaybillRepositoryInterface;
use Modules\Paybill\Repositories\Eloquent\PaybillRepository;
use Modules\Paybill\Services\Contracts\PaybillServiceInterface;
use Modules\Paybill\Services\PaybillService;

class PaybillServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaybillRepositoryInterface::class, PaybillRepository::class);
        $this->app->bind(PaybillServiceInterface::class, PaybillService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Paybill');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}