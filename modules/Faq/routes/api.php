<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\FaqApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_faq.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('faq', FaqApiController::class);
    });