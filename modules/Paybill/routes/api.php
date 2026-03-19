<?php

use Illuminate\Support\Facades\Route;
use Modules\Paybill\Http\Controllers\PaybillApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_paybill.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('paybill', PaybillApiController::class);
    });