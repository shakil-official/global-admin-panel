<?php

namespace App\Providers;


use App\Engine\About\Repositories\Contracts\AboutRepositoryInterface;
use App\Engine\About\Repositories\Eloquent\AboutRepository;
use App\Engine\Category\Repositories\Contracts\CategoryRepositoryInterface;
use App\Engine\Category\Repositories\Eloquent\CategoryRepository;
use App\Engine\Contact\Repositories\Contracts\ContactRepositoryInterface;
use App\Engine\Contact\Repositories\Eloquent\ContactRepository;
use App\Engine\Feedback\Repositories\Contracts\FeedbackRepositoryInterface;
use App\Engine\Feedback\Repositories\Eloquent\FeedbackRepository;
use App\Engine\GlobalCategory\Repositories\Contracts\GlobalCategoryRepositoryInterface;
use App\Engine\GlobalCategory\Repositories\Eloquent\GlobalCategoryRepository;
use App\Engine\GlobalSubCategory\Repositories\Contracts\GlobalSubCategoryRepositoryInterface;
use App\Engine\GlobalSubCategory\Repositories\Eloquent\GlobalSubCategoryRepository;
use App\Engine\SliderImage\Repositories\Contracts\SliderImageRepositoryInterface;
use App\Engine\SliderImage\Repositories\Eloquent\SliderImageRepository;
use App\Engine\TermsAndCondition\Repositories\Contracts\TermsAndConditionRepositoryInterface;
use App\Engine\TermsAndCondition\Repositories\Eloquent\TermsAndConditionRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(ContactRepositoryInterface::class, ContactRepository::class);
        $this->app->bind(AboutRepositoryInterface::class, AboutRepository::class);
        $this->app->bind(TermsAndConditionRepositoryInterface::class, TermsAndConditionRepository::class);
        $this->app->bind(SliderImageRepositoryInterface::class, SliderImageRepository::class);
        $this->app->bind(GlobalCategoryRepositoryInterface::class, GlobalCategoryRepository::class);
        $this->app->bind(GlobalSubCategoryRepositoryInterface::class, GlobalSubCategoryRepository::class);
        $this->app->bind(FeedbackRepositoryInterface::class, FeedbackRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
