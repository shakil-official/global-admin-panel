<?php

use Illuminate\Support\Facades\Route;
use Modules\General\Http\Controllers\GeneralController;

Route::middleware(['web','auth', 'verified'])->prefix('/general')->group(function () {
    Route::get('/', [GeneralController::class, 'index'])->name('general.view');
//    Route::get('/add', [GeneralController::class, 'add'])->name('general.add');
//    Route::post('/store', [GeneralController::class, 'store'])->name('general.store');
    Route::get('/list', [GeneralController::class, 'dataTableList'])->name('general.list');
    Route::get('/edit/{id}', [GeneralController::class, 'edit'])->name('general.edit');
    Route::post('/update/{id}', [GeneralController::class, 'update'])->name('general.update');
//    Route::delete('/delete', [GeneralController::class, 'delete'])->name('general.delete');
});
