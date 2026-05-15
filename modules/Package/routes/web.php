<?php

use Illuminate\Support\Facades\Route;
use Modules\Package\Http\Controllers\PackageController;

Route::middleware(['web','auth', 'verified'])->prefix('/package')->group(function () {
    Route::get('/', [PackageController::class, 'index'])->middleware('permission:package.view')->name('package.index');
    Route::get('/add', [PackageController::class, 'add'])->middleware('permission:package.create')->name('package.add');
    Route::post('/store', [PackageController::class, 'store'])->middleware('permission:package.create')->name('package.store');
    Route::get('/list', [PackageController::class, 'dataTableList'])->middleware('permission:package.view')->name('package.list');
    Route::get('/edit/{id}', [PackageController::class, 'edit'])->middleware('permission:package.update')->name('package.edit');
    Route::post('/update/{id}', [PackageController::class, 'update'])->middleware('permission:package.update')->name('package.update');
    Route::delete('/delete', [PackageController::class, 'delete'])->middleware('permission:package.delete')->name('package.delete');
    Route::get('/request', [PackageController::class, 'packageRequest'])->middleware('permission:package.request')->name('package.request');
    Route::get('/list-request', [PackageController::class, 'packageRequestDataTableList'])->middleware('permission:package.list-request')->name('package.list-request');
});
