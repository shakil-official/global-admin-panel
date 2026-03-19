<?php

use Illuminate\Support\Facades\Route;
use Modules\Feedback\Http\Controllers\FeedbackApiController;

Route::middleware('api')->prefix('api/v1')
    ->as('api_feedback.')
    ->group(function () {
        Route::apiResource('feedback', FeedbackApiController::class);
    });
