<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryApiController;

Route::prefix('v1')
    ->as('api_category.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('category', CategoryApiController::class);
    });