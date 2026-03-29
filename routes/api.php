<?php

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AboutApiController;
use App\Http\Controllers\API\HomeApiController;


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