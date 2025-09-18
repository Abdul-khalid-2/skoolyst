<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SchoolController;
use App\Models\School;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Add the custom create route separately
Route::resource('schools', SchoolController::class)->middleware(['auth', 'verified'])->except(['create']);
Route::get('/dashboard/create', [SchoolController::class, 'create'])->middleware(['auth', 'verified'])->name('schools.create');

// Events Routes
Route::resource('events', EventController::class);

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

// Or use the resource route with names explicitly defined
// Route::prefix('schools/{school}')->group(function () {
//     Route::resource('branches', BranchController::class)->names([
//         'index' => 'schools.branches.index',
//         'create' => 'schools.branches.create',
//         'store' => 'schools.branches.store',
//         'show' => 'schools.branches.show',
//         'edit' => 'schools.branches.edit',
//         'update' => 'schools.branches.update',
//         'destroy' => 'schools.branches.destroy'
//     ]);
// });

// // Branch Routes within School context
// Route::prefix('schools/{school}')->group(function () {
//     Route::resource('branches', BranchController::class)->except(['show']);
//     Route::get('branches/{branch}', [BranchController::class, 'show'])->name('schools.branches.show');
// });

// // API route for fetching branches (for event forms)
Route::get('/api/schools/{school}/branches', function (School $school) {
    return $school->branches()->where('status', 'active')->get();
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
