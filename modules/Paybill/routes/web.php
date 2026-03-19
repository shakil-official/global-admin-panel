<?php

use Illuminate\Support\Facades\Route;
use Modules\Paybill\Http\Controllers\PayBillController;

Route::middleware(['web','auth', 'verified'])->prefix('/paybill')->group(function () {
    Route::get('/', [PayBillController::class, 'index'])->name('paybill.index');
    Route::get('/add', [PayBillController::class, 'add'])->name('paybill.add');
    Route::post('/store', [PayBillController::class, 'store'])->name('paybill.store');
    Route::get('/list', [PayBillController::class, 'dataTableList'])->name('paybill.list');
    Route::get('/edit/{id}', [PayBillController::class, 'edit'])->name('paybill.edit');
    Route::post('/update/{id}', [PayBillController::class, 'update'])->name('paybill.update');
    Route::delete('/delete', [PayBillController::class, 'delete'])->name('paybill.delete');
});
