<?php

namespace Modules\Faq;

use Illuminate\Support\ServiceProvider;
use Modules\Faq\Repositories\Contracts\FaqRepositoryInterface;
use Modules\Faq\Repositories\Eloquent\FaqRepository;
use Modules\Faq\Services\Contracts\FaqServiceInterface;
use Modules\Faq\Services\FaqService;

class FaqServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(FaqServiceInterface::class, FaqService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Faq');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}