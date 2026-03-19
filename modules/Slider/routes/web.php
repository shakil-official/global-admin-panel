<?php

use Illuminate\Support\Facades\Route;
use Modules\Slider\Http\Controllers\SliderController;

Route::middleware(['web','auth', 'verified'])->prefix('/slider')->group(function () {
    Route::get('/', [SliderController::class, 'index'])->name('slider.index');
    Route::get('/add', [SliderController::class, 'add'])->name('slider.add');
    Route::post('/store', [SliderController::class, 'store'])->name('slider.store');
    Route::get('/list', [SliderController::class, 'dataTableList'])->name('slider.list');
    Route::get('/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit');
    Route::post('/update/{id}', [SliderController::class, 'update'])->name('slider.update');
    Route::delete('/delete', [SliderController::class, 'delete'])->name('slider.delete');
});