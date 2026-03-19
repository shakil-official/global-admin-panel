<?php

namespace Modules\SubCategory;

use Illuminate\Support\ServiceProvider;
use Modules\SubCategory\Repositories\Contracts\SubCategoryRepositoryInterface;
use Modules\SubCategory\Repositories\Eloquent\SubCategoryRepository;
use Modules\SubCategory\Services\Contracts\SubCategoryServiceInterface;
use Modules\SubCategory\Services\SubCategoryService;

class SubCategoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SubCategoryRepositoryInterface::class, SubCategoryRepository::class);
        $this->app->bind(SubCategoryServiceInterface::class, SubCategoryService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'SubCategory');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}
