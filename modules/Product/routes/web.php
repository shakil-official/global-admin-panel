<?php

use Illuminate\Support\Facades\Route;
use Modules\Product\Http\Controllers\ProductController;

Route::middleware(['web','auth', 'verified'])->prefix('/product')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('product.index');
    Route::get('/add', [ProductController::class, 'add'])->name('product.add');
    Route::post('/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/list', [ProductController::class, 'dataTableList'])->name('product.list');
    Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/delete', [ProductController::class, 'delete'])->name('product.delete');
});