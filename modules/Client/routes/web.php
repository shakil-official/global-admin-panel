<?php

use Illuminate\Support\Facades\Route;
use Modules\Client\Http\Controllers\ClientController;

Route::middleware(['web','auth', 'verified'])->prefix('/client')->group(function () {
    Route::get('/', [ClientController::class, 'index'])->name('client.index');
    Route::get('/add', [ClientController::class, 'add'])->name('client.add');
    Route::post('/store', [ClientController::class, 'store'])->name('client.store');
    Route::get('/list', [ClientController::class, 'dataTableList'])->name('client.list');
    Route::get('/edit/{id}', [ClientController::class, 'edit'])->name('client.edit');
    Route::post('/update/{id}', [ClientController::class, 'update'])->name('client.update');
    Route::delete('/delete', [ClientController::class, 'delete'])->name('client.delete');
});