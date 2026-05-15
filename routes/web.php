<?php

use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\Basic\TemplateController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Debug\PermissionDebugController;
use App\Http\Controllers\Debug\SimpleDebugController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\TermsAndCondition\TermsAndConditionController;
use App\Http\Controllers\Test\PermissionTestController;
use App\Http\Controllers\Test\SimpleBlogTestController;
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


    Route::get('/privacy-policy', [AboutController::class, 'edit'])->middleware('permission:about.update')->name('about.edit');
    Route::post('/privacy-policy/update/{id}', [AboutController::class, 'update'])->middleware('permission:about.update')->name('about.update');
    Route::get('/section/term', [TermsAndConditionController::class, 'edit'])->middleware('permission:about.update')->name('section_term.edit');
    Route::post('/terms/and/condition/update/{id}', [TermsAndConditionController::class, 'update'])->middleware('permission:about.update')->name('section.term.update');

    /*
     * Contact page Start
     */
    Route::get('/contact', [ContactController::class, 'index'])->middleware('permission:contact.view')->name('contact.view');
    Route::get('/contact/list', [ContactController::class, 'dataTableList'])->middleware('permission:contact.view')->name('contact.list');
    Route::get('/contact/edit/{id}', [ContactController::class, 'edit'])->middleware('permission:contact.edit')->name('contact.edit');
    Route::post('/contact/update/{id}', [ContactController::class, 'update'])->middleware('permission:contact.update')->name('contact.update');
    Route::delete('/contact/delete', [ContactController::class, 'delete'])->middleware('permission:contact.delete')->name('contact.delete');

    /*
     * End Contact page
     */


    Route::post('/file/upload', [FileController::class, 'upload'])->name('file.upload');
    Route::post('/file/delete', [FileController::class, 'delete'])->name('file.delete');
    Route::get('/basic', [TemplateController::class, 'index'])->name('basic.index');


    /* start here */
    Route::get('logs', [LogViewerController::class, 'index']);

});


Route::prefix('')->name('here.')->middleware(['auth', 'verified'])->group(function () {
});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Test route for permissions
Route::get('/test/permissions', [PermissionTestController::class, 'index'])->middleware(['auth', 'verified'])->name('test.permissions');

// Debug routes for permission testing
Route::get('/debug/permissions', [PermissionDebugController::class, 'index'])->middleware(['auth', 'verified'])->name('debug.permissions');
Route::get('/debug/permission/{permission}', [PermissionDebugController::class, 'testPermission'])->middleware(['auth', 'verified'])->name('debug.test-permission');

// Simple test routes without permission middleware
Route::get('/test/blog-add', [SimpleBlogTestController::class, 'testAdd'])->middleware(['auth', 'verified'])->name('test.blog-add');
Route::get('/test/middleware', [SimpleBlogTestController::class, 'testMiddleware'])->middleware(['auth', 'verified'])->name('test.middleware');

// Simple debug route
Route::get('/debug/simple', [SimpleDebugController::class, 'test'])->middleware(['auth', 'verified'])->name('debug.simple');
Route::get('/debug/route', [SimpleDebugController::class, 'testRoute'])->middleware(['auth', 'verified'])->name('debug.route');

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';


Route::get('/{any}', function () {
    return view('frontend.app');
})->where('any', '^(?!api).*');
