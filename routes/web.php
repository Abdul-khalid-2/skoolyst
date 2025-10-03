<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SchoolController;
use App\Models\School;
use App\Http\Controllers\PageController;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard.dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');


    // Add the custom create route separately
    Route::resource('schools', SchoolController::class);
    // Route::get('/school/create', [SchoolController::class, 'create'])->name('schools.create');

    // Events Routes
    Route::resource('events', EventController::class);


    // routes/web.php
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::get('/pages/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::get('/pages/destroy', [PageController::class, 'destroy'])->name('pages.destroy');
    Route::post('/pages/store', [PageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page:slug}', [PageController::class, 'show'])->name('pages.show'); // Add this line

    // Branch Routes within School context
    Route::prefix('schools/{school}')->group(function () {
        Route::get('branches', [BranchController::class, 'index'])->name('schools.branches.index');
        Route::get('branches/create', [BranchController::class, 'create'])->name('schools.branches.create');
        Route::post('branches', [BranchController::class, 'store'])->name('schools.branches.store');
        Route::get('branches/{branch}', [BranchController::class, 'show'])->name('schools.branches.show');
        Route::get('branches/{branch}/edit', [BranchController::class, 'edit'])->name('schools.branches.edit');
        Route::put('branches/{branch}', [BranchController::class, 'update'])->name('schools.branches.update');
        Route::delete('branches/{branch}', [BranchController::class, 'destroy'])->name('schools.branches.destroy');
    });

    // // API route for fetching branches (for event forms)
    Route::get('/api/schools/{school}/branches', function (School $school) {
        return $school->branches()->where('status', 'active')->get();
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__ . '/auth.php';
