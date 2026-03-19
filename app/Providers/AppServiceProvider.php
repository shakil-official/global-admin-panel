<?php

namespace App\Providers;

use App\Engine\About\Services\AboutService;
use App\Engine\About\Services\Contracts\AboutServiceInterface;
use App\Engine\Category\Services\CategoryService;
use App\Engine\Category\Services\Contracts\CategoryServiceInterface;
use App\Engine\Contact\Services\ContactService;
use App\Engine\Contact\Services\Contracts\ContactServiceInterface;
use App\Engine\Feedback\Services\Contracts\FeedbackServiceInterface;
use App\Engine\Feedback\Services\FeedbackService;
use App\Engine\GlobalCategory\Services\Contracts\GlobalCategoryServiceInterface;
use App\Engine\GlobalCategory\Services\GlobalCategoryService;
use App\Engine\GlobalSubCategory\Services\Contracts\GlobalSubCategoryServiceInterface;
use App\Engine\GlobalSubCategory\Services\GlobalSubCategoryService;
use App\Engine\SliderImage\Services\Contracts\SliderImageServiceInterface;
use App\Engine\SliderImage\Services\SliderImageService;
use App\Engine\SubCategory\Services\Contracts\SubCategoryServiceInterface;
use App\Engine\SubCategory\Services\SubCategoryService;
use App\Engine\TermsAndCondition\Services\Contracts\TermsAndConditionServiceInterface;
use App\Engine\TermsAndCondition\Services\TermsAndConditionService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
        $this->app->bind(AboutServiceInterface::class, AboutService::class);
        $this->app->bind(TermsAndConditionServiceInterface::class, TermsAndConditionService::class);
        $this->app->bind(SliderImageServiceInterface::class, SliderImageService::class);
        $this->app->bind(GlobalCategoryServiceInterface::class, GlobalCategoryService::class);
        $this->app->bind(GlobalSubCategoryServiceInterface::class, GlobalSubCategoryService::class);
        $this->app->bind(FeedbackServiceInterface::class, FeedbackService::class);
        $this->app->bind(SubCategoryServiceInterface::class, SubCategoryService::class);

        // Load all module service providers dynamically
        $modulesPath = base_path('modules');

        if (File::exists($modulesPath)) {
            foreach (File::directories($modulesPath) as $moduleDir) {
                $moduleName = basename($moduleDir);
                $providerClass = "Modules\\{$moduleName}\\{$moduleName}ServiceProvider";
                if (class_exists($providerClass)) {
                    $this->app->register($providerClass);
                }
            }
        }

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
