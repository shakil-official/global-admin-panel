<?php

use Illuminate\Support\Facades\Route;
use Modules\Slider\Http\Controllers\SliderApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_slider.')

    ->group(function () {
        Route::apiResource('slider', SliderApiController::class);
    });
