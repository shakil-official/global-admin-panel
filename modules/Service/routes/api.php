<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\ServiceApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_service.')
    ->middleware(\Illuminate\Http\Middleware\HandleCors::class)
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('services', ServiceApiController::class);
    });
