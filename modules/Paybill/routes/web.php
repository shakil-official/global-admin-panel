<?php

use Illuminate\Support\Facades\Route;
use Modules\Paybill\Http\Controllers\PaybillController;

Route::middleware(['web','auth', 'verified'])->prefix('/paybill')->group(function () {
    Route::get('/', [PaybillController::class, 'index'])->middleware('permission:paybill.view')->name('paybill.index');
    Route::get('/add', [PaybillController::class, 'add'])->middleware('permission:paybill.create')->name('paybill.add');
    Route::post('/store', [PaybillController::class, 'store'])->middleware('permission:paybill.create')->name('paybill.store');
    Route::get('/list', [PaybillController::class, 'dataTableList'])->middleware('permission:paybill.view')->name('paybill.list');
    Route::get('/edit/{id}', [PaybillController::class, 'edit'])->middleware('permission:paybill.update')->name('paybill.edit');
    Route::post('/update/{id}', [PaybillController::class, 'update'])->middleware('permission:paybill.update')->name('paybill.update');
    Route::delete('/delete', [PaybillController::class, 'delete'])->middleware('permission:paybill.delete')->name('paybill.delete');
});
