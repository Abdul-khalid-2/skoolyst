<?php

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AboutApiController;
use App\Http\Controllers\API\HomeApiController;
use App\Http\Controllers\OAuthController;

// "Login with Skoolyst" — steps 2 & 3. See app/Http/Controllers/OAuthController.php
// and integrate_login_with_skoolyst.md for the full consumer-app integration guide.
Route::prefix('oauth')->group(function () {
    Route::post('/token', [OAuthController::class, 'token'])
        ->middleware('throttle:30,1')
        ->name('api.oauth.token');
    Route::get('/user', [OAuthController::class, 'user'])
        ->middleware(['auth:sanctum', 'throttle:60,1'])
        ->name('api.oauth.user');
});


Route::prefix('home')->group(function () {
    Route::get('/', [HomeApiController::class, 'home']);
    Route::get('/search', [HomeApiController::class, 'search']);
    Route::get('/how-it-works', [HomeApiController::class, 'howItWorks']);
    Route::get('/cities', [HomeApiController::class, 'getCities']);
    Route::get('/curriculums', [HomeApiController::class, 'getCurriculums']);
    Route::get('/school-types', [HomeApiController::class, 'getSchoolTypes']);
});

Route::prefix('about')->group(function () {

    Route::get('/insights', [AboutApiController::class, 'insights']);
    Route::get('/digital-transformation', [AboutApiController::class, 'digitalTransformation']);
    Route::get('/school-community', [AboutApiController::class, 'schoolCommunity']);
    Route::get('/school-marketing', [AboutApiController::class, 'schoolMarketing']);

});