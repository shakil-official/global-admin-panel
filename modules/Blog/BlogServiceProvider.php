<?php

namespace Modules\Blog;

use Illuminate\Support\ServiceProvider;
use Modules\Blog\Repositories\Contracts\BlogRepositoryInterface;
use Modules\Blog\Repositories\Eloquent\BlogRepository;
use Modules\Blog\Services\Contracts\BlogServiceInterface;
use Modules\Blog\Services\BlogService;

class BlogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(BlogServiceInterface::class, BlogService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Blog');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}