<?php

use Illuminate\Support\Facades\Route;
use Modules\Package\Http\Controllers\PackageController;

Route::middleware(['web','auth', 'verified'])->prefix('/package')->group(function () {
    Route::get('/', [PackageController::class, 'index'])->name('package.index');
    Route::get('/add', [PackageController::class, 'add'])->name('package.add');
    Route::post('/store', [PackageController::class, 'store'])->name('package.store');
    Route::get('/list', [PackageController::class, 'dataTableList'])->name('package.list');
    Route::get('/edit/{id}', [PackageController::class, 'edit'])->name('package.edit');
    Route::post('/update/{id}', [PackageController::class, 'update'])->name('package.update');
    Route::delete('/delete', [PackageController::class, 'delete'])->name('package.delete');
});