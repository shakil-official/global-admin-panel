<?php

use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\SettingController;

Route::middleware(['web','auth', 'verified'])->prefix('/setting')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->middleware('permission:setting.view')->name('setting.index');
    Route::get('/add', [SettingController::class, 'add'])->middleware('permission:setting.create')->name('setting.add');
    Route::post('/store', [SettingController::class, 'store'])->middleware('permission:setting.create')->name('setting.store');
    Route::get('/list', [SettingController::class, 'dataTableList'])->middleware('permission:setting.view')->name('setting.list');
    Route::get('/edit/{id}', [SettingController::class, 'edit'])->middleware('permission:setting.update')->name('setting.edit');
    Route::post('/update/{id}', [SettingController::class, 'update'])->middleware('permission:setting.update')->name('setting.update');
    Route::delete('/delete', [SettingController::class, 'delete'])->middleware('permission:setting.delete')->name('setting.delete');
});
