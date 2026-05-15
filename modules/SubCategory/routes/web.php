<?php

use Illuminate\Support\Facades\Route;
use Modules\SubCategory\Http\Controllers\SubCategoryController;

Route::middleware(['web','auth', 'verified'])->prefix('/subcategory')->group(function () {
    Route::get('/', [SubCategoryController::class, 'index'])->middleware('permission:subcategory.view')->name('subcategory.index');
    Route::get('/add', [SubCategoryController::class, 'add'])->middleware('permission:subcategory.create')->name('subcategory.add');
    Route::post('/store', [SubCategoryController::class, 'store'])->middleware('permission:subcategory.create')->name('subcategory.store');
    Route::get('/list', [SubCategoryController::class, 'dataTableList'])->middleware('permission:subcategory.view')->name('subcategory.list');
    Route::get('/edit/{id}', [SubCategoryController::class, 'edit'])->middleware('permission:subcategory.update')->name('subcategory.edit');
    Route::post('/update/{id}', [SubCategoryController::class, 'update'])->middleware('permission:subcategory.update')->name('subcategory.update');
    Route::delete('/delete', [SubCategoryController::class, 'delete'])->middleware('permission:subcategory.delete')->name('subcategory.delete');
});
