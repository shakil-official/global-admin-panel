<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::middleware(['web','auth', 'verified'])->prefix('/category')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->middleware('permission:category.view')->name('category.index');
    Route::get('/add', [CategoryController::class, 'add'])->middleware('permission:category.create')->name('category.add');
    Route::post('/store', [CategoryController::class, 'store'])->middleware('permission:category.create')->name('category.store');
    Route::get('/list', [CategoryController::class, 'dataTableList'])->middleware('permission:category.view')->name('category.list');
    Route::get('/edit/{id}', [CategoryController::class, 'edit'])->middleware('permission:category.update')->name('category.edit');
    Route::post('/update/{id}', [CategoryController::class, 'update'])->middleware('permission:category.update')->name('category.update');
    Route::delete('/delete', [CategoryController::class, 'delete'])->middleware('permission:category.delete')->name('category.delete');
});