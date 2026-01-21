<?php

use App\Http\Controllers\About\AboutController;
use App\Http\Controllers\Basic\TemplateController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Contact\ContactController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Feedback\FeedbackController;
use App\Http\Controllers\File\FileController;
use App\Http\Controllers\SliderImage\SliderImageController;
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
    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/category/list', [CategoryController::class, 'dataTableList'])->name('category.list');
    Route::get('/category/add', [CategoryController::class, 'add'])->name('category.add');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('/category/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/delete', [CategoryController::class, 'delete'])->name('category.delete');

    Route::get('/about', [AboutController::class, 'edit'])->name('about.edit');
    Route::post('/about/update/{id}', [AboutController::class, 'update'])->name('about.update');

    Route::get('/section/term', [TermsAndConditionController::class, 'edit'])->name('section_term.edit');
    Route::post('/terms/and/condition/update/{id}', [TermsAndConditionController::class, 'update'])->name('section.term..update');



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


    Route::get('/slider', [SliderImageController::class, 'index'])->name('slider_image.index');
    Route::get('/slider/list', [SliderImageController::class, 'dataTableList'])->name('slider_image.list');
    Route::get('/slider/add', [SliderImageController::class, 'add'])->name('slider_image.add');
    Route::post('/slider/store', [SliderImageController::class, 'store'])->name('slider_image.store');
    Route::get('/slider/edit/{id}', [SliderImageController::class, 'edit'])->name('slider_image.edit');
    Route::post('/slider/update/{id}', [SliderImageController::class, 'update'])->name('slider_image.update');
    Route::delete('/slider/delete', [SliderImageController::class, 'delete'])->name('slider_image.delete');

    Route::post('/file/upload', [FileController::class, 'upload'])->name('file.upload');
    Route::post('/file/delete', [FileController::class, 'delete'])->name('file.delete');
    Route::get('/basic', [TemplateController::class, 'index'])->name('basic.index');


    /* start here */
    Route::get('/service/type', [App\Http\Controllers\GlobalCategory\GlobalCategoryController::class, 'index'])->name('global_category.index');
    Route::get('/service/type/list', [App\Http\Controllers\GlobalCategory\GlobalCategoryController::class, 'dataTableList'])->name('global_category.list');
    Route::get('/service/type/add', [App\Http\Controllers\GlobalCategory\GlobalCategoryController::class, 'add'])->name('global_category.add');
    Route::post('/service/type/store', [App\Http\Controllers\GlobalCategory\GlobalCategoryController::class, 'store'])->name('global_category.store');
    Route::get('/service/type/edit/{id}', [App\Http\Controllers\GlobalCategory\GlobalCategoryController::class, 'edit'])->name('global_category.edit');
    Route::post('/service/type/update/{id}', [App\Http\Controllers\GlobalCategory\GlobalCategoryController::class, 'update'])->name('global_category.update');
    Route::delete('/service/type/delete', [App\Http\Controllers\GlobalCategory\GlobalCategoryController::class, 'delete'])->name('global_category.delete');

    Route::get('/services', [App\Http\Controllers\GlobalSubCategory\GlobalSubCategoryController::class, 'index'])->name('global_sub_category.index');
    Route::get('/services/list', [App\Http\Controllers\GlobalSubCategory\GlobalSubCategoryController::class, 'dataTableList'])->name('global_sub_category.list');
    Route::get('/services/add', [App\Http\Controllers\GlobalSubCategory\GlobalSubCategoryController::class, 'add'])->name('global_sub_category.add');
    Route::post('/services/store', [App\Http\Controllers\GlobalSubCategory\GlobalSubCategoryController::class, 'store'])->name('global_sub_category.store');
    Route::get('/services/edit/{id}', [App\Http\Controllers\GlobalSubCategory\GlobalSubCategoryController::class, 'edit'])->name('global_sub_category.edit');
    Route::post('/services/update/{id}', [App\Http\Controllers\GlobalSubCategory\GlobalSubCategoryController::class, 'update'])->name('global_sub_category.update');
    Route::delete('/services/delete', [App\Http\Controllers\GlobalSubCategory\GlobalSubCategoryController::class, 'delete'])->name('global_sub_category.delete');


    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/list', [FeedbackController::class, 'dataTableList'])->name('feedback.list');
    Route::get('/feedback/add', [FeedbackController::class, 'add'])->name('feedback.add');
    Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/edit/{id}', [FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::post('/feedback/update/{id}', [FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/delete', [FeedbackController::class, 'delete'])->name('feedback.delete');

    Route::get('logs', [LogViewerController::class, 'index']);


    Route::get('/sub/category', [App\Http\Controllers\SubCategory\SubCategoryController::class, 'index'])->name('subcategory.index');
    Route::get('/sub/category/list', [App\Http\Controllers\SubCategory\SubCategoryController::class, 'dataTableList'])->name('SubCategory.list');
    Route::get('/sub/category/add', [App\Http\Controllers\SubCategory\SubCategoryController::class, 'add'])->name('subcategory.add');
    Route::post('/sub/category/store', [App\Http\Controllers\SubCategory\SubCategoryController::class, 'store'])->name('subcategory.store');
    Route::get('/sub/category/edit/{id}', [App\Http\Controllers\SubCategory\SubCategoryController::class, 'edit'])->name('subcategory.edit');
    Route::post('/sub/category/update/{id}', [App\Http\Controllers\SubCategory\SubCategoryController::class, 'update'])->name('subcategory.update');
    Route::delete('/sub/category/delete', [App\Http\Controllers\SubCategory\SubCategoryController::class, 'delete'])->name('SubCategory.delete');


});


Route::prefix('')->name('here.')->middleware(['auth', 'verified'])->group(function () {});
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/admin.php';
require __DIR__ . '/auth.php';


Route::get('/{any}', function () {
    return view('frontend.app');
})->where('any', '^(?!api).*');
