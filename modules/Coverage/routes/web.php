<?php

use Illuminate\Support\Facades\Route;
use Modules\Coverage\Http\Controllers\CoverageController;

Route::middleware(['web','auth', 'verified'])->prefix('/coverage')->group(function () {
    Route::get('/', [CoverageController::class, 'index'])->name('coverage.index');
    Route::get('/add', [CoverageController::class, 'add'])->name('coverage.add');
    Route::post('/store', [CoverageController::class, 'store'])->name('coverage.store');
    Route::get('/list', [CoverageController::class, 'dataTableList'])->name('coverage.list');
    Route::get('/edit/{id}', [CoverageController::class, 'edit'])->name('coverage.edit');
    Route::post('/update/{id}', [CoverageController::class, 'update'])->name('coverage.update');
    Route::delete('/delete', [CoverageController::class, 'delete'])->name('coverage.delete');
});