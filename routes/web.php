<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ContactInquiryController;
use App\Http\Controllers\DashboardControlle;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SchoolController;
use App\Models\School;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Website\AboutController;
use App\Http\Controllers\Website\BrowseSchoolController;
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\HomeControllere;
use App\Http\Controllers\Website\ReviewController;
use App\Http\Controllers\Website\AdvertisementPageController;

use App\Http\Controllers\UserProfileController;

use App\Http\Controllers\SchoolImageGalleryController;
use App\Http\Controllers\Website\WebsiteAnnouncementController;

use App\Http\Controllers\Website\WebsiteBlogPostController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::middleware(['auth', 'verified', 'role:super-admin|school-admin'])->group(function () {

    Route::get('/dashboard', [DashboardControlle::class, 'index'])->name('dashboard');


    // Add the custom create route separately
    Route::resource('schools', SchoolController::class);

    // Route::get('/school/create', [SchoolController::class, 'create'])->name('schools.create');

    // Events Routes
    Route::resource('events', EventController::class);
    Route::put('/events/{event}/status', [EventController::class, 'updateStatus'])->name('events.update_status');


    // routes/web.php
    Route::get('/pages/{event_id}', [PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create/{school_uuid}/{event_id}', [PageController::class, 'create'])->name('pages.create');
    Route::get('/pages/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{id}', [PageController::class, 'update'])->name('pages.update');
    Route::delete('/pages/{id}', [PageController::class, 'destroy'])->name('pages.destroy');
    Route::post('/pages/store', [PageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{slug}/{page_uuid}', [PageController::class, 'show'])->name('pages.show');

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

Route::middleware('guest')->group(function () {
    Route::post('/schools/register', [SchoolController::class, 'register'])->name('school.register');
});

Route::get('/', [HomeController::class, 'home'])->name('website.home');
Route::get('/search', [HomeController::class, 'search'])->name('search.schools');

Route::get('/all/schools', [BrowseSchoolController::class, 'index'])->name('browseSchools.index');
Route::get('/browse/schools/search', [BrowseSchoolController::class, 'search'])->name('browseSchools.search');
Route::get('/school/profile/{uuid}', [BrowseSchoolController::class, 'show'])->name('browseSchools.show');

Route::get('/about', [AboutController::class, 'index'])->name('about');


Route::get('/how_it_works', [HomeController::class, 'howItWorks'])->name('website.how_it_works');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    Route::prefix('school-image-galleries')->group(function () {
        Route::get('/', [SchoolImageGalleryController::class, 'index'])->name('school-image-galleries.index');
        Route::post('/', [SchoolImageGalleryController::class, 'store'])->name('school-image-galleries.store');
        Route::get('/{schoolImageGallery}', [SchoolImageGalleryController::class, 'show'])->name('school-image-galleries.show');
        Route::put('/{schoolImageGallery}', [SchoolImageGalleryController::class, 'update'])->name('school-image-galleries.update');
        Route::delete('/{schoolImageGallery}', [SchoolImageGalleryController::class, 'destroy'])->name('school-image-galleries.destroy');
    });
});



// User Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('user_profile')->group(function () {
        Route::get('/', [UserProfileController::class, 'show'])->name('user_profile.show');
        Route::get('/edit', [UserProfileController::class, 'edit'])->name('user_profile.edit');
        Route::put('/update', [UserProfileController::class, 'update'])->name('user_profile.update');
    });
});
// advertisement pages
Route::get('/event_list/{id}', [AdvertisementPageController::class, 'index'])->name('advertisement_pages.index');
Route::get('/page-view/{slug}/{page_uuid}', [AdvertisementPageController::class, 'show'])->name('advertisement_pages.show');

Route::post('/schools/{school}/reviews', [ReviewController::class, 'store'])->name('reviews.store');


Route::post('/contact-inquiry', [ContactInquiryController::class, 'store'])->name('contact.inquiry.store');

// Admin routes 
Route::middleware(['auth'])->prefix('school-admin')->group(function () {
    Route::get('/inquiries', [ContactInquiryController::class, 'index'])->name('admin.inquiries.index');
    Route::get('/inquiries/{inquiry}', [ContactInquiryController::class, 'show'])->name('admin.inquiries.show');
    Route::patch('/inquiries/{inquiry}/status', [ContactInquiryController::class, 'updateStatus'])->name('admin.inquiries.updateStatus');
    Route::post('/inquiries/{inquiry}/assign', [ContactInquiryController::class, 'assign'])->name('admin.inquiries.assign');
    Route::get('/inquiries/stats', [ContactInquiryController::class, 'getStats'])->name('admin.inquiries.stats');
});


// Notification routes
Route::middleware(['auth'])->prefix('admin/inquiries')->group(function () {
    Route::get('/notification-count', [ContactInquiryController::class, 'getNotificationCount'])->name('admin.inquiries.notification-count');
    Route::post('/{inquiry}/mark-read', [ContactInquiryController::class, 'markAsRead'])->name('admin.inquiries.mark-read');
});



Route::get('/announcement/{announcement}', [WebsiteAnnouncementController::class, 'show'])->name('website.announcements.show');
Route::post('announcements/{uuid}/comments', [WebsiteAnnouncementController::class, 'storeComment'])->name('announcements.comments.store');
Route::resource('announcements', AnnouncementController::class);
// Route::post('announcements/{uuid}/comments', [AnnouncementController::class, 'storeComment'])
//     ->name('announcements.comments.store');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    // Blog Categories
    Route::resource('blog-categories', \App\Http\Controllers\BlogCategoryController::class);

    // Blog Posts
    Route::resource('blog-posts', \App\Http\Controllers\BlogPostController::class);

    // Comments
    Route::get('comments', [\App\Http\Controllers\CommentController::class, 'index'])->name('comments.index');
    Route::put('comments/{comment}/status', [\App\Http\Controllers\CommentController::class, 'updateStatus'])->name('comments.update-status');
    Route::delete('comments/{comment}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comments.destroy');
});


// Blog Routes
Route::prefix('blog')->name('website.blog.')->group(function () {
    Route::get('/', [WebsiteBlogPostController::class, 'index'])->name('index');
    Route::get('/{slug}', [WebsiteBlogPostController::class, 'show'])->name('show');
    Route::get('/category/{slug}', [WebsiteBlogPostController::class, 'category'])->name('category');
    Route::get('/tag/{tag}', [WebsiteBlogPostController::class, 'tag'])->name('tag');


    Route::post('/{post}/comment', [\App\Http\Controllers\Website\BlogCommentController::class, 'store'])->name('comment.store');
});

Route::view('privacy', 'website.privacy')->name('website.privacy');
Route::view('terms', 'website.terms')->name('website.terms');

require __DIR__ . '/auth.php';
