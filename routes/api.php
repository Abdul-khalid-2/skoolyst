<?php

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// routes/api.php
Route::get('/schools/{school}/branches', function (School $school) {
    return $school->branches()->where('status', 'active')->get();
});
