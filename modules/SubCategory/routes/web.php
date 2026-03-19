<?php

use Illuminate\Support\Facades\Route;
use Modules\SubCategory\Http\Controllers\SubCategoryController;

Route::middleware(['web','auth', 'verified'])->prefix('/sub-category')->group(function () {
    Route::get('/', [SubCategoryController::class, 'index'])->name('sub-category.index');
    Route::get('/add', [SubCategoryController::class, 'add'])->name('sub-category.add');
    Route::post('/store', [SubCategoryController::class, 'store'])->name('sub-category.store');
    Route::get('/list', [SubCategoryController::class, 'dataTableList'])->name('sub-category.list');
    Route::get('/edit/{id}', [SubCategoryController::class, 'edit'])->name('sub-category.edit');
    Route::post('/update/{id}', [SubCategoryController::class, 'update'])->name('sub-category.update');
    Route::delete('/delete', [SubCategoryController::class, 'delete'])->name('sub-category.delete');
});