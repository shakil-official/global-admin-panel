<?php

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Controllers\ClientController;

Route::middleware(['web','auth', 'verified'])->prefix('/client')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->middleware('permission:client.view')->name('client.index');
    Route::get('/add', [ClientController::class, 'add'])->middleware('permission:client.create')->name('client.add');
    Route::post('/store', [ClientController::class, 'store'])->middleware('permission:client.create')->name('client.store');
    Route::get('/list', [ClientController::class, 'dataTableList'])->middleware('permission:client.view')->name('client.list');
    Route::get('/edit/{id}', [ClientController::class, 'edit'])->middleware('permission:client.update')->name('client.edit');
    Route::post('/update/{id}', [ClientController::class, 'update'])->middleware('permission:client.update')->name('client.update');
    Route::delete('/delete', [ClientController::class, 'delete'])->middleware('permission:client.delete')->name('client.delete');
});
