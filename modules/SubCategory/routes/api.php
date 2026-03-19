<?php

use Illuminate\Support\Facades\Route;
use Modules\SubCategory\Http\Controllers\SubCategoryApiController;

Route::prefix('v1')
    ->as('api_sub-category.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('sub-category', SubCategoryApiController::class);
    });