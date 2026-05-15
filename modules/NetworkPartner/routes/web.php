<?php

use Illuminate\Support\Facades\Route;
use Modules\NetworkPartner\Http\Controllers\NetworkPartnerController;

Route::middleware(['web','auth', 'verified'])->prefix('/networkpartner')->group(function () {
    Route::get('/', [NetworkPartnerController::class, 'index'])->middleware('permission:networkpartner.view')->name('network-partner.index');
    Route::get('/add', [NetworkPartnerController::class, 'add'])->middleware('permission:networkpartner.create')->name('networkpartner.add');
    Route::post('/store', [NetworkPartnerController::class, 'store'])->middleware('permission:networkpartner.create')->name('networkpartner.store');
    Route::get('/list', [NetworkPartnerController::class, 'dataTableList'])->middleware('permission:networkpartner.view')->name('networkpartner.list');
    Route::get('/edit/{id}', [NetworkPartnerController::class, 'edit'])->middleware('permission:networkpartner.update')->name('networkpartner.edit');
    Route::post('/update/{id}', [NetworkPartnerController::class, 'update'])->middleware('permission:networkpartner.update')->name('networkpartner.update');
    Route::delete('/delete', [NetworkPartnerController::class, 'delete'])->middleware('permission:networkpartner.delete')->name('networkpartner.delete');
});
