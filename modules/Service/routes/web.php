<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\ServiceController;

Route::middleware(['web','auth', 'verified'])->prefix('/service')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->middleware('permission:service.view')->name('service.index');
    Route::get('/add', [ServiceController::class, 'add'])->middleware('permission:service.create')->name('service.add');
    Route::post('/store', [ServiceController::class, 'store'])->middleware('permission:service.create')->name('service.store');
    Route::get('/list', [ServiceController::class, 'dataTableList'])->middleware('permission:service.view')->name('service.list');
    Route::get('/edit/{id}', [ServiceController::class, 'edit'])->middleware('permission:service.update')->name('service.edit');
    Route::post('/update/{id}', [ServiceController::class, 'update'])->middleware('permission:service.update')->name('service.update');
    Route::delete('/delete', [ServiceController::class, 'delete'])->middleware('permission:service.delete')->name('service.delete');
});
