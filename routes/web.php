<?php

use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\Basic\TemplateController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Feedback\FeedbackController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\TermsAndCondition\TermsAndConditionController;
use Illuminate\Support\Facades\Route;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider, and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/admin', function () {
    return "Admin";
})->name('main');

Route::middleware(['auth', 'verified'])->group(function () {


    Route::get('/privacy-policy', [AboutController::class, 'edit'])->name('about.edit');
    Route::post('/privacy-policy/update/{id}', [AboutController::class, 'update'])->name('about.update');
    Route::get('/section/term', [TermsAndConditionController::class, 'edit'])->name('section_term.edit');
    Route::post('/terms/and/condition/update/{id}', [TermsAndConditionController::class, 'update'])->name('section.term.update');

    /*
     * Contact page Start
     */
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::get('/contact/list', [ContactController::class, 'dataTableList'])->name('contact.list');
    Route::get('/contact/edit/{id}', [ContactController::class, 'edit'])->name('contact.edit');
    Route::post('/contact/update/{id}', [ContactController::class, 'update'])->name('contact.update');
    Route::delete('/contact/delete', [ContactController::class, 'delete'])->name('contact.delete');

    /*
     * End Contact page
     */


    Route::post('/file/upload', [FileController::class, 'upload'])->name('file.upload');
    Route::post('/file/delete', [FileController::class, 'delete'])->name('file.delete');
    Route::get('/basic', [TemplateController::class, 'index'])->name('basic.index');


    /* start here */
    Route::get('logs', [LogViewerController::class, 'index']);

});


Route::prefix('')->name('here.')->middleware(['auth', 'verified'])->group(function () {});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';


Route::get('/{any}', function () {
    return view('frontend.app');
})->where('any', '^(?!api).*');
