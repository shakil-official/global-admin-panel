<?php

use Illuminate\Support\Facades\Route;
use Modules\Feedback\Http\Controllers\FeedbackController;

Route::middleware(['web','auth', 'verified'])->prefix('/feedback')->group(function () {
    Route::get('/', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/add', [FeedbackController::class, 'add'])->name('feedback.add');
    Route::post('/store', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/list', [FeedbackController::class, 'dataTableList'])->name('feedback.list');
    Route::get('/edit/{id}', [FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::post('/update/{id}', [FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/delete', [FeedbackController::class, 'delete'])->name('feedback.delete');
});