<?php

use Illuminate\Support\Facades\Route;
use Modules\Tag\Http\Controllers\TagController;

Route::middleware(['web','auth', 'verified'])->prefix('/tag')->group(function () {
    Route::get('/', [TagController::class, 'index'])->middleware('permission:tag.view')->name('tag.index');
    Route::get('/add', [TagController::class, 'add'])->middleware('permission:tag.create')->name('tag.add');
    Route::post('/store', [TagController::class, 'store'])->middleware('permission:tag.create')->name('tag.store');
    Route::get('/list', [TagController::class, 'dataTableList'])->middleware('permission:tag.view')->name('tag.list');
    Route::get('/edit/{id}', [TagController::class, 'edit'])->middleware('permission:tag.update')->name('tag.edit');
    Route::post('/update/{id}', [TagController::class, 'update'])->middleware('permission:tag.update')->name('tag.update');
    Route::delete('/delete', [TagController::class, 'delete'])->middleware('permission:tag.delete')->name('tag.delete');
});
