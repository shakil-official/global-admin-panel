<?php

use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Controllers\BranchApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_branch.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('branch', BranchApiController::class);
    });