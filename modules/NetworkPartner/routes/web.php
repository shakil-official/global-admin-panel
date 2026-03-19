<?php

use Illuminate\Support\Facades\Route;
use Modules\NetworkPartner\Http\Controllers\NetworkPartnerController;

Route::middleware(['web','auth', 'verified'])->prefix('/network/partner')->group(function () {
    Route::get('/', [NetworkPartnerController::class, 'index'])->name('network-partner.index');
    Route::get('/add', [NetworkPartnerController::class, 'add'])->name('network-partner.add');
    Route::post('/store', [NetworkPartnerController::class, 'store'])->name('network-partner.store');
    Route::get('/list', [NetworkPartnerController::class, 'dataTableList'])->name('network-partner.list');
    Route::get('/edit/{id}', [NetworkPartnerController::class, 'edit'])->name('network-partner.edit');
    Route::post('/update/{id}', [NetworkPartnerController::class, 'update'])->name('network-partner.update');
    Route::delete('/delete', [NetworkPartnerController::class, 'delete'])->name('network-partner.delete');
});
