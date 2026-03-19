<?php

use Illuminate\Support\Facades\Route;
use Modules\FaqCategory\Http\Controllers\FaqCategoryController;

Route::middleware(['web','auth', 'verified'])->prefix('/faq-category')->group(function () {
    Route::get('/', [FaqCategoryController::class, 'index'])->name('faq-category.index');
    Route::get('/add', [FaqCategoryController::class, 'add'])->name('faq-category.add');
    Route::post('/store', [FaqCategoryController::class, 'store'])->name('faq-category.store');
    Route::get('/list', [FaqCategoryController::class, 'dataTableList'])->name('faq-category.list');
    Route::get('/edit/{id}', [FaqCategoryController::class, 'edit'])->name('faq-category.edit');
    Route::post('/update/{id}', [FaqCategoryController::class, 'update'])->name('faq-category.update');
    Route::delete('/delete', [FaqCategoryController::class, 'delete'])->name('faq-category.delete');
});