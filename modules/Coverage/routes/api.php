<?php

use Illuminate\Support\Facades\Route;
use Modules\Coverage\Http\Controllers\CoverageApiController;

Route::middleware('api')->
    prefix('api/v1')
    ->as('api_coverage.')
    ->group(function () {
        Route::apiResource('coverage', CoverageApiController::class);
    });
