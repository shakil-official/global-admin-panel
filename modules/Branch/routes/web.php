<?php

use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Controllers\BranchController;

Route::middleware(['web','auth', 'verified'])->prefix('/branch')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->name('branch.index');
    Route::get('/add', [BranchController::class, 'add'])->name('branch.add');
    Route::post('/store', [BranchController::class, 'store'])->name('branch.store');
    Route::get('/list', [BranchController::class, 'dataTableList'])->name('branch.list');
    Route::get('/edit/{id}', [BranchController::class, 'edit'])->name('branch.edit');
    Route::post('/update/{id}', [BranchController::class, 'update'])->name('branch.update');
    Route::delete('/delete', [BranchController::class, 'delete'])->name('branch.delete');
});