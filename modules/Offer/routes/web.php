<?php

use Illuminate\Support\Facades\Route;
use Modules\Offer\Http\Controllers\OfferController;

Route::middleware(['web','auth', 'verified'])->prefix('/offer')->group(function () {
    Route::get('/', [OfferController::class, 'index'])->name('offer.index');
    Route::get('/add', [OfferController::class, 'add'])->name('offer.add');
    Route::post('/store', [OfferController::class, 'store'])->name('offer.store');
    Route::get('/list', [OfferController::class, 'dataTableList'])->name('offer.list');
    Route::get('/edit/{id}', [OfferController::class, 'edit'])->name('offer.edit');
    Route::post('/update/{id}', [OfferController::class, 'update'])->name('offer.update');
    Route::delete('/delete', [OfferController::class, 'delete'])->name('offer.delete');
});