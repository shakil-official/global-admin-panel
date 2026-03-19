<?php

use Illuminate\Support\Facades\Route;
use Modules\FaqCategory\Http\Controllers\FaqCategoryApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_faq-category.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('faq-category', FaqCategoryApiController::class);
    });