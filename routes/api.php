<?php

use App\Http\Controllers\Api\AboutController;
use App\Http\Controllers\Api\CookieConsentController;
use App\Http\Controllers\Api\TermsController;
use App\Http\Controllers\Api\V1\SliderImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider, and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/terms', [TermsController::class, 'getTerms']);
Route::get('/privacy-policy', [AboutController::class, 'getAbout']);

Route::post('/v1/insert-contact', [AboutController::class, 'insertContact']);
Route::post('/v1/connection-request', [AboutController::class, 'connectionRequest']);

Route::get('/partner-images', function () {
    $files = File::files(public_path('images/partner'));
    return collect($files)->map(function ($file) {
        return asset('images/partner/' . $file->getFilename());
    });
});


Route::post('/cookie-consent', [CookieConsentController::class, 'store']);


