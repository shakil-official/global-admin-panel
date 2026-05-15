<?php

use Illuminate\Support\Facades\Route;
use Modules\Slider\Http\Controllers\SliderController;

Route::middleware(['web','auth', 'verified'])->prefix('/slider')->group(function () {
    Route::get('/', [SliderController::class, 'index'])->middleware('permission:slider.view')->name('slider.index');
    Route::get('/add', [SliderController::class, 'add'])->middleware('permission:slider.create')->name('slider.add');
    Route::post('/store', [SliderController::class, 'store'])->middleware('permission:slider.create')->name('slider.store');
    Route::get('/list', [SliderController::class, 'dataTableList'])->middleware('permission:slider.view')->name('slider.list');
    Route::get('/edit/{id}', [SliderController::class, 'edit'])->middleware('permission:slider.update')->name('slider.edit');
    Route::post('/update/{id}', [SliderController::class, 'update'])->middleware('permission:slider.update')->name('slider.update');
    Route::delete('/delete', [SliderController::class, 'delete'])->middleware('permission:slider.delete')->name('slider.delete');
});
