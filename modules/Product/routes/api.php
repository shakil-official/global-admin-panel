<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductApiController;

Route::prefix('v1')
    ->as('api_product.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('product', ProductApiController::class);
    });