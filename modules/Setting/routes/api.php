<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\SettingApiController;

Route::prefix('v1')
    ->as('api_setting.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('setting', SettingApiController::class);
    });