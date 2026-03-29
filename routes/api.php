<?php

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AboutApiController;


Route::prefix('about')->group(function () {

    Route::get('/insights', [AboutApiController::class, 'insights']);
    Route::get('/digital-transformation', [AboutApiController::class, 'digitalTransformation']);
    Route::get('/school-community', [AboutApiController::class, 'schoolCommunity']);
    Route::get('/school-marketing', [AboutApiController::class, 'schoolMarketing']);

});