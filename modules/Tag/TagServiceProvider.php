<?php

namespace Modules\Tag;

use Illuminate\Support\ServiceProvider;
use Modules\Tag\Repositories\Contracts\TagRepositoryInterface;
use Modules\Tag\Repositories\Eloquent\TagRepository;
use Modules\Tag\Services\Contracts\TagServiceInterface;
use Modules\Tag\Services\TagService;

class TagServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(TagServiceInterface::class, TagService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Tag');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}
