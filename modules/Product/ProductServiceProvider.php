<?php

namespace Modules\Product;

use Illuminate\Support\ServiceProvider;
use Modules\Product\Repositories\Contracts\ProductRepositoryInterface;
use Modules\Product\Repositories\Eloquent\ProductRepository;
use Modules\Product\Services\Contracts\ProductServiceInterface;
use Modules\Product\Services\ProductService;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Product');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}