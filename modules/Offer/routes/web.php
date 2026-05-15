<?php

use Illuminate\Support\Facades\Route;
use Modules\Offer\Http\Controllers\OfferController;

Route::middleware(['web','auth', 'verified'])->prefix('/offer')->group(function () {
    Route::get('/', [OfferController::class, 'index'])->middleware('permission:offer.view')->name('offer.index');
    Route::get('/add', [OfferController::class, 'add'])->middleware('permission:offer.create')->name('offer.add');
    Route::post('/store', [OfferController::class, 'store'])->middleware('permission:offer.create')->name('offer.store');
    Route::get('/list', [OfferController::class, 'dataTableList'])->middleware('permission:offer.view')->name('offer.list');
    Route::get('/edit/{id}', [OfferController::class, 'edit'])->middleware('permission:offer.update')->name('offer.edit');
    Route::post('/update/{id}', [OfferController::class, 'update'])->middleware('permission:offer.update')->name('offer.update');
    Route::delete('/delete', [OfferController::class, 'delete'])->middleware('permission:offer.delete')->name('offer.delete');
});
