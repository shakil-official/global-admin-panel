<?php

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Controllers\ClientApiController;

Route::prefix('v1')
    ->as('api_client.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('client', ClientApiController::class);
    });