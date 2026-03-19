<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\SettingController;

Route::middleware(['web','auth', 'verified'])->prefix('/setting')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('setting.index');
    Route::get('/add', [SettingController::class, 'add'])->name('setting.add');
    Route::post('/store', [SettingController::class, 'store'])->name('setting.store');
    Route::get('/list', [SettingController::class, 'dataTableList'])->name('setting.list');
    Route::get('/edit/{id}', [SettingController::class, 'edit'])->name('setting.edit');
    Route::post('/update/{id}', [SettingController::class, 'update'])->name('setting.update');
    Route::delete('/delete', [SettingController::class, 'delete'])->name('setting.delete');
});