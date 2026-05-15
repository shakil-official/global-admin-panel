<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\FaqController;

Route::middleware(['web','auth', 'verified'])->prefix('/faq')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->middleware('permission:faq.view')->name('faq.index');
    Route::get('/add', [FaqController::class, 'add'])->middleware('permission:faq.create')->name('faq.add');
    Route::post('/store', [FaqController::class, 'store'])->middleware('permission:faq.create')->name('faq.store');
    Route::get('/list', [FaqController::class, 'dataTableList'])->middleware('permission:faq.view')->name('faq.list');
    Route::get('/edit/{id}', [FaqController::class, 'edit'])->middleware('permission:faq.update')->name('faq.edit');
    Route::post('/update/{id}', [FaqController::class, 'update'])->middleware('permission:faq.update')->name('faq.update');
    Route::delete('/delete', [FaqController::class, 'delete'])->middleware('permission:faq.delete')->name('faq.delete');
});
