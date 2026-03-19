<?php

use Illuminate\Support\Facades\Route;
use Modules\Offer\Http\Controllers\OfferApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_offer.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('offer', OfferApiController::class);
    });