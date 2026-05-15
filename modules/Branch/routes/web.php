<?php

use Illuminate\Support\Facades\Route;
use Modules\Branch\Http\Controllers\BranchController;

Route::middleware(['web','auth', 'verified'])->prefix('/branch')->group(function () {
    Route::get('/', [BranchController::class, 'index'])->middleware('permission:branch.view')->name('branch.index');
    Route::get('/add', [BranchController::class, 'add'])->middleware('permission:branch.create')->name('branch.add');
    Route::post('/store', [BranchController::class, 'store'])->middleware('permission:branch.create')->name('branch.store');
    Route::get('/list', [BranchController::class, 'dataTableList'])->middleware('permission:branch.view')->name('branch.list');
    Route::get('/edit/{id}', [BranchController::class, 'edit'])->middleware('permission:branch.update')->name('branch.edit');
    Route::post('/update/{id}', [BranchController::class, 'update'])->middleware('permission:branch.update')->name('branch.update');
    Route::delete('/delete', [BranchController::class, 'delete'])->middleware('permission:branch.delete')->name('branch.delete');
});