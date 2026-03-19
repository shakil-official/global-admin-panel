<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::middleware(['web','auth', 'verified'])->prefix('/category')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/add', [CategoryController::class, 'add'])->name('category.add');
    Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/list', [CategoryController::class, 'dataTableList'])->name('category.list');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/delete', [CategoryController::class, 'delete'])->name('category.delete');
});