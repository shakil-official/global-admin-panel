<?php

namespace Modules\Feedback;

use Illuminate\Support\ServiceProvider;
use Modules\Feedback\Repositories\Contracts\FeedbackRepositoryInterface;
use Modules\Feedback\Repositories\Eloquent\FeedbackRepository;
use Modules\Feedback\Services\Contracts\FeedbackServiceInterface;
use Modules\Feedback\Services\FeedbackService;

class FeedbackServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(FeedbackRepositoryInterface::class, FeedbackRepository::class);
        $this->app->bind(FeedbackServiceInterface::class, FeedbackService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Feedback');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}
