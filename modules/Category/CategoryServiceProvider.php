<?php

namespace Modules\Category;

use Illuminate\Support\ServiceProvider;
use Modules\Category\Repositories\Contracts\CategoryRepositoryInterface;
use Modules\Category\Repositories\Eloquent\CategoryRepository;
use Modules\Category\Services\Contracts\CategoryServiceInterface;
use Modules\Category\Services\CategoryService;

class CategoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Category');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}
