<?php

use Illuminate\Support\Facades\Route;
use Modules\General\Http\Controllers\GeneralApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_general.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        // single data
        Route::get('/general/single/data', [GeneralApiController::class, 'show']);
    });
