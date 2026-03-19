<?php

namespace Modules\Branch;

use Illuminate\Support\ServiceProvider;
use Modules\Branch\Repositories\Contracts\BranchRepositoryInterface;
use Modules\Branch\Repositories\Eloquent\BranchRepository;
use Modules\Branch\Services\Contracts\BranchServiceInterface;
use Modules\Branch\Services\BranchService;

class BranchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BranchRepositoryInterface::class, BranchRepository::class);
        $this->app->bind(BranchServiceInterface::class, BranchService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Branch');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}