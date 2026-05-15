<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;

Route::middleware(['web','auth', 'verified'])->prefix('/blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->middleware('permission:blog.view')->name('blog.index');
    Route::get('/add', [BlogController::class, 'add'])->middleware('permission:blog.create')->name('blog.add');
    Route::post('/store', [BlogController::class, 'store'])->middleware('permission:blog.create')->name('blog.store');
    Route::get('/list', [BlogController::class, 'dataTableList'])->middleware('permission:blog.view')->name('blog.list');
    Route::get('/edit/{id}', [BlogController::class, 'edit'])->middleware('permission:blog.update')->name('blog.edit');
    Route::post('/update/{id}', [BlogController::class, 'update'])->middleware('permission:blog.update')->name('blog.update');
    Route::delete('/delete', [BlogController::class, 'delete'])->middleware('permission:blog.delete')->name('blog.delete');
});