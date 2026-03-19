<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogApiController;

Route::middleware('api')
    ->prefix('api/v1')
    ->as('api_blog.')
//    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('blog', [BlogApiController::class, 'index'])->name('blog.index');
        Route::get('blog/{slug}', [BlogApiController::class, 'show'])->name('blog.show');

    });
