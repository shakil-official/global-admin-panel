<?php

namespace Modules\FaqCategory;

use Illuminate\Support\ServiceProvider;
use Modules\FaqCategory\Repositories\Contracts\FaqCategoryRepositoryInterface;
use Modules\FaqCategory\Repositories\Eloquent\FaqCategoryRepository;
use Modules\FaqCategory\Services\Contracts\FaqCategoryServiceInterface;
use Modules\FaqCategory\Services\FaqCategoryService;

class FaqCategoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FaqCategoryRepositoryInterface::class, FaqCategoryRepository::class);
        $this->app->bind(FaqCategoryServiceInterface::class, FaqCategoryService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'FaqCategory');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}