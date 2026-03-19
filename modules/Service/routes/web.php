<?php

use Illuminate\Support\Facades\Route;
use Modules\Service\Http\Controllers\ServiceController;

Route::middleware(['web','auth', 'verified'])->prefix('/service')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('service.index');
    Route::get('/add', [ServiceController::class, 'add'])->name('service.add');
    Route::post('/store', [ServiceController::class, 'store'])->name('service.store');
    Route::get('/list', [ServiceController::class, 'dataTableList'])->name('service.list');
    Route::get('/edit/{id}', [ServiceController::class, 'edit'])->name('service.edit');
    Route::post('/update/{id}', [ServiceController::class, 'update'])->name('service.update');
    Route::delete('/delete', [ServiceController::class, 'delete'])->name('service.delete');
});