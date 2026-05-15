<?php

use Illuminate\Support\Facades\Route;
use Modules\Feedback\Http\Controllers\FeedbackController;

Route::middleware(['web','auth', 'verified'])->prefix('/feedback')->group(function () {
    Route::get('/', [FeedbackController::class, 'index'])->middleware('permission:feedback.view')->name('feedback.index');
    Route::get('/add', [FeedbackController::class, 'add'])->middleware('permission:feedback.create')->name('feedback.add');
    Route::post('/store', [FeedbackController::class, 'store'])->middleware('permission:feedback.create')->name('feedback.store');
    Route::get('/list', [FeedbackController::class, 'dataTableList'])->middleware('permission:feedback.view')->name('feedback.list');
    Route::get('/edit/{id}', [FeedbackController::class, 'edit'])->middleware('permission:feedback.update')->name('feedback.edit');
    Route::post('/update/{id}', [FeedbackController::class, 'update'])->middleware('permission:feedback.update')->name('feedback.update');
    Route::delete('/delete', [FeedbackController::class, 'delete'])->middleware('permission:feedback.delete')->name('feedback.delete');
});
