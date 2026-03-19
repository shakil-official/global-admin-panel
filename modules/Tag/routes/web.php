<?php

use Illuminate\Support\Facades\Route;
use Modules\Tag\Http\Controllers\TagController;

Route::middleware(['web','auth', 'verified'])->prefix('/tag')->group(function () {
    Route::get('/', [TagController::class, 'index'])->name('tag.index');
    Route::get('/add', [TagController::class, 'add'])->name('tag.add');
    Route::post('/store', [TagController::class, 'store'])->name('tag.store');
    Route::get('/list', [TagController::class, 'dataTableList'])->name('tag.list');
    Route::get('/edit/{id}', [TagController::class, 'edit'])->name('tag.edit');
    Route::post('/update/{id}', [TagController::class, 'update'])->name('tag.update');
    Route::delete('/delete', [TagController::class, 'delete'])->name('tag.delete');
});