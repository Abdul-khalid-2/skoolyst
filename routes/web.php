<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SchoolController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/schools', [SchoolController::class, 'index'])->middleware(['auth', 'verified'])->name('schools');
Route::get('/schools/show', [SchoolController::class, 'show'])->middleware(['auth', 'verified'])->name('schools.show');
Route::get('/schools/create', [SchoolController::class, 'create'])->middleware(['auth', 'verified'])->name('schools.add');
Route::post('/schools/store', [SchoolController::class, 'store'])->middleware(['auth', 'verified'])->name('schools.store');
Route::get('/schools/edit', [SchoolController::class, 'edit'])->middleware(['auth', 'verified'])->name('schools.edit');
Route::post('/schools/update', [SchoolController::class, 'update'])->middleware(['auth', 'verified'])->name('schools.update');
Route::post('/schools/delete', [SchoolController::class, 'destroy'])->middleware(['auth', 'verified'])->name('schools.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
