<?php

namespace Modules\Slider;

use Illuminate\Support\ServiceProvider;
use Modules\Slider\Repositories\Contracts\SliderRepositoryInterface;
use Modules\Slider\Repositories\Eloquent\SliderRepository;
use Modules\Slider\Services\Contracts\SliderServiceInterface;
use Modules\Slider\Services\SliderService;

class SliderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SliderRepositoryInterface::class, SliderRepository::class);
        $this->app->bind(SliderServiceInterface::class, SliderService::class);
    }

    public function boot(): void
    {
        // Load module routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

        // Load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Slider');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }


}
