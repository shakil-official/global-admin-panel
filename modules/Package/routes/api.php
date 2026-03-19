<?php

use Illuminate\Support\Facades\Route;
use Modules\Package\Http\Controllers\PackageApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_package.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('package', PackageApiController::class);
    });