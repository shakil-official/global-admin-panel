<?php

use Illuminate\Support\Facades\Route;
use Modules\FaqCategory\Http\Controllers\FaqCategoryController;

Route::middleware(['web','auth', 'verified'])->prefix('/faqcategory')->group(function () {
    Route::get('/', [FaqCategoryController::class, 'index'])->middleware('permission:faqcategory.view')->name('faq-category.index');
    Route::get('/add', [FaqCategoryController::class, 'add'])->middleware('permission:faqcategory.create')->name('faqcategory.add');
    Route::post('/store', [FaqCategoryController::class, 'store'])->middleware('permission:faqcategory.create')->name('faqcategory.store');
    Route::get('/list', [FaqCategoryController::class, 'dataTableList'])->middleware('permission:faqcategory.view')->name('faqcategory.list');
    Route::get('/edit/{id}', [FaqCategoryController::class, 'edit'])->middleware('permission:faqcategory.update')->name('faqcategory.edit');
    Route::post('/update/{id}', [FaqCategoryController::class, 'update'])->middleware('permission:faqcategory.update')->name('faqcategory.update');
    Route::delete('/delete', [FaqCategoryController::class, 'delete'])->middleware('permission:faqcategory.delete')->name('faqcategory.delete');
});
