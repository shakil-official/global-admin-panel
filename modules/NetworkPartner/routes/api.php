<?php

use Illuminate\Support\Facades\Route;
use Modules\NetworkPartner\Http\Controllers\NetworkPartnerApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_network-partner.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('network-partner', NetworkPartnerApiController::class);
    });