<?php

use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;

Route::middleware(['web','auth', 'verified'])->prefix('/blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/add', [BlogController::class, 'add'])->name('blog.add');
    Route::post('/store', [BlogController::class, 'store'])->name('blog.store');
    Route::get('/list', [BlogController::class, 'dataTableList'])->name('blog.list');
    Route::get('/edit/{id}', [BlogController::class, 'edit'])->name('blog.edit');
    Route::post('/update/{id}', [BlogController::class, 'update'])->name('blog.update');
    Route::delete('/delete', [BlogController::class, 'delete'])->name('blog.delete');
});