<?php

use Illuminate\Support\Facades\Route;
use Modules\Coverage\Http\Controllers\CoverageController;

Route::middleware(['web','auth', 'verified'])->prefix('/coverage')->group(function () {
    Route::get('/', [CoverageController::class, 'index'])->middleware('permission:coverage.view')->name('coverage.index');
    Route::get('/add', [CoverageController::class, 'add'])->middleware('permission:coverage.create')->name('coverage.add');
    Route::post('/store', [CoverageController::class, 'store'])->middleware('permission:coverage.create')->name('coverage.store');
    Route::get('/list', [CoverageController::class, 'dataTableList'])->middleware('permission:coverage.view')->name('coverage.list');
    Route::get('/edit/{id}', [CoverageController::class, 'edit'])->middleware('permission:coverage.update')->name('coverage.edit');
    Route::post('/update/{id}', [CoverageController::class, 'update'])->middleware('permission:coverage.update')->name('coverage.update');
    Route::delete('/delete', [CoverageController::class, 'delete'])->middleware('permission:coverage.delete')->name('coverage.delete');
});
