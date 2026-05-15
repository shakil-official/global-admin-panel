<?php

use Illuminate\Support\Facades\Route;
use Modules\Seo\Http\Controllers\SeoController;

Route::middleware(['web','auth', 'verified'])->prefix('/seo')->group(function () {
//    Route::get('/', [SeoController::class, 'index'])->name('seo.index');
//    Route::get('/add', [SeoController::class, 'add'])->name('seo.add');
//    Route::post('/store', [SeoController::class, 'store'])->name('seo.store');
//
    Route::get('/seo/information', [SeoController::class, 'edit'])->name('seo.information');
    Route::post('/update/{id}', [SeoController::class, 'update'])->name('seo.update');
});
