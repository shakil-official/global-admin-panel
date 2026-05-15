<?php

use Illuminate\Support\Facades\Route;
use Modules\Seo\Http\Controllers\SeoApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_seo.')
//    ->middleware('auth:sanctum')
    ->group(function () {

        Route::get('/settings/seo', [SeoApiController::class, 'settings'])->name('settings');

    });
