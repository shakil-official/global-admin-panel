<?php

use Illuminate\Support\Facades\Route;
use Modules\Tag\Http\Controllers\TagApiController;

Route::prefix('v1')
    ->as('api_tag.')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('tag', TagApiController::class);
    });