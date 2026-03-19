<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\FaqController;

Route::middleware(['web','auth', 'verified'])->prefix('/faq')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('faq.index');
    Route::get('/add', [FaqController::class, 'add'])->name('faq.add');
    Route::post('/store', [FaqController::class, 'store'])->name('faq.store');
    Route::get('/list', [FaqController::class, 'dataTableList'])->name('faq.list');
    Route::get('/edit/{id}', [FaqController::class, 'edit'])->name('faq.edit');
    Route::post('/update/{id}', [FaqController::class, 'update'])->name('faq.update');
    Route::delete('/delete', [FaqController::class, 'delete'])->name('faq.delete');
});