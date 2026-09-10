<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ContactInquiryController;
use App\Http\Controllers\DashboardControlle;
use App\Http\Controllers\EventController;
use App\Http\Controllers\McqController;
use App\Http\Controllers\McqDashboardController;
use App\Http\Controllers\MockTestController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/public/en', '/', 301);
Route::redirect('/en', '/', 301);
Route::redirect('/en/', '/', 301);
Route::redirect('/public', '/', 301);

use App\Http\Controllers\CurriculumController;
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


use App\Http\Controllers\SchoolMcqController;
use App\Http\Controllers\SchoolStudyMaterialController;
use App\Http\Controllers\StudyMaterialController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoCategoryController;
use App\Http\Controllers\VideoCommentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoReactionController;
use App\Http\Controllers\Website\VideoWebsiteController;
use App\Http\Controllers\Website\TestimonialController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Legacy /public/* URLs were indexed by Google when the document root was
// misconfigured. Redirect them 301 to the same path without the /public prefix
// so search-engine traffic lands on the real page instead of a 404.
Route::get('/public/{any}', function ($any) {
    return redirect('/' . ltrim($any, '/'), 301);
})->where('any', '.*');

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localizationRedirect', 'localeViewPath']
], function () {

    Route::middleware(['auth', 'verified', 'role:super-admin|school-admin|shop-owner'])->group(function () {

        Route::get('/dashboard', [DashboardControlle::class, 'index'])->name('dashboard');


        // Add the custom create route separately
        Route::resource('schools', SchoolController::class);
        Route::post('curriculums/quick-store', [CurriculumController::class, 'quickStore'])->name('curriculums.quick-store');
        // User Management Routes
        Route::resource('users', UserController::class);
        Route::patch('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update-status');

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
        // Route::prefix('schools/{school}')->group(function () {
        Route::get('schools/{school}/branches', [BranchController::class, 'index'])->name('schools.branches.index');
        Route::get('schools/{school}/branches/create', [BranchController::class, 'create'])->name('schools.branches.create');
        Route::post('schools/{school}/branches', [BranchController::class, 'store'])->name('schools.branches.store');
        Route::get('schools/{school}/branches/{branch}', [BranchController::class, 'show'])->name('schools.branches.show');
        Route::get('schools/{school}/branches/{branch}/edit', [BranchController::class, 'edit'])->name('schools.branches.edit');
        Route::put('schools/{school}/branches/{branch}', [BranchController::class, 'update'])->name('schools.branches.update');
        Route::delete('schools/{school}/branches/{branch}', [BranchController::class, 'destroy'])->name('schools.branches.destroy');
        // });

        // // API route for fetching branches (for event forms)
        Route::get('/api/schools/{school}/branches', function (School $school) {
            return $school->branches()->where('status', 'active')->get();
        });

        // Shop/Product/Coupon/Order dashboard routes removed (Step 3, 2026-09-10).
        // Backend controllers/models/migrations/database untouched; only the admin
        // routes, views and nav were removed.

        Route::resource('dashboard/announcements', AnnouncementController::class);

        // Admin Blog (posts/categories/comments) dashboard routes removed
        // (Step 2, 2026-09-10). Blog models/migrations/database untouched.


        // Videos Routes

        // Public routes
        Route::get('dashboard/videos/', [VideoController::class, 'index'])->name('admin.videos.index');

        // Protected routes

        Route::get('dashboard/videos/my-videos', [VideoController::class, 'myVideos'])->name('admin.videos.my-videos');
        Route::get('dashboard/videos/create', [VideoController::class, 'create'])->name('admin.videos.create');
        Route::post('dashboard/videos/', [VideoController::class, 'store'])->name('admin.videos.store');
        Route::get('dashboard/videos/{video}/edit', [VideoController::class, 'edit'])->name('admin.videos.edit');
        Route::put('dashboard/videos/{video}', [VideoController::class, 'update'])->name('admin.videos.update');
        Route::delete('dashboard/videos/{video}', [VideoController::class, 'destroy'])->name('admin.videos.destroy');

        // Comments
        Route::post('dashboard/videos/{video}/comments', [VideoCommentController::class, 'store'])->name('admin.videos.comments.store');
        Route::post('dashboard/videos/comments/{comment}/like', [VideoCommentController::class, 'like'])->name('admin.videos.comments.like');
        Route::delete('dashboard/videos/comments/{comment}', [VideoCommentController::class, 'destroy'])->name('admin.videos.comments.destroy');


        Route::get('dashboard/videos/{slug}', [VideoController::class, 'show'])->name('admin.videos.show');

        // Admin video categories
        // Route::resource('dashboard/video-categories', VideoCategoryController::class)->except(['admin.show']);
        Route::prefix('dashboard/video-categories')->name('video-categories.')->group(function () {
            Route::get('/', [VideoCategoryController::class, 'index'])->name('index');
            Route::post('/', [VideoCategoryController::class, 'store'])->name('store');
            Route::get('/{videoCategory}/edit', [VideoCategoryController::class, 'edit'])->name('edit');
            Route::put('/{videoCategory}', [VideoCategoryController::class, 'update'])->name('update');
            Route::delete('/{videoCategory}', [VideoCategoryController::class, 'destroy'])->name('destroy');

            // Optional bulk actions
            Route::post('/bulk-action', [VideoCategoryController::class, 'bulkAction'])->name('bulk.action');
            Route::post('/update-sort', [VideoCategoryController::class, 'updateSortOrder'])->name('update.sort');
        });
    });

    Route::middleware('guest')->group(function () {
        Route::post('/schools/register', [SchoolController::class, 'register'])->name('school.register');
    });

    Route::get('/', [HomeController::class, 'home'])->name('website.home');
    Route::get('/search/suggest', [HomeController::class, 'searchSuggest'])
        ->middleware('throttle:90,1')
        ->name('search.schools.suggest');
    Route::get('/search', [HomeController::class, 'search'])
        ->middleware('throttle:120,1')
        ->name('search.schools');

    // ===== ADD THIS CONTACT ROUTE =====
    Route::get('/contact', [ContactInquiryController::class, 'create'])->name('website.contact');

    Route::get('/all/schools', [BrowseSchoolController::class, 'index'])->name('browseSchools.index');
    Route::get('/browse/schools/search', [BrowseSchoolController::class, 'search'])->name('browseSchools.search');
    Route::get('/school/profile/{uuid}', [BrowseSchoolController::class, 'show'])->name('browseSchools.show');

    Route::get('/about', [AboutController::class, 'index'])->name('about');
    // Route::get('/insights', [AboutController::class, 'about'])->name('about');
    Route::get('/insights/digital-transformation', [AboutController::class, 'digitalTransformation'])->name('insights.digital_transformation');
    Route::get('/insights/school-community', [AboutController::class, 'schoolCommunity'])->name('insights.school_community');
    Route::get('/insights/school-marketing', [AboutController::class, 'schoolMarketing'])->name('insights.school_marketing');

    Route::get('/how_it_works', [HomeController::class, 'howItWorks'])->name('website.how_it_works');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Route::middleware(['auth'])->group(function () {
    //     Route::prefix('school-image-galleries')->group(function () {
    //         Route::get('/', [SchoolImageGalleryController::class, 'index'])->name('school-image-galleries.index');
    //         Route::post('/', [SchoolImageGalleryController::class, 'store'])->name('school-image-galleries.store');
    //         Route::get('/{schoolImageGallery}', [SchoolImageGalleryController::class, 'show'])->name('school-image-galleries.show');
    //         Route::put('/{schoolImageGallery}', [SchoolImageGalleryController::class, 'update'])->name('school-image-galleries.update');
    //         Route::delete('/{schoolImageGallery}', [SchoolImageGalleryController::class, 'destroy'])->name('school-image-galleries.destroy');
    //     });
    // });

    // User Profile Routes
    Route::middleware(['auth'])->group(function () {
        Route::prefix('user_profile')->group(function () {
            Route::get('/', [UserProfileController::class, 'show'])->name('user_profile.show');
            Route::get('/edit', [UserProfileController::class, 'edit'])->name('user_profile.edit');
            Route::put('/update', [UserProfileController::class, 'update'])->name('user_profile.update');
            Route::get('/certificate/{attempt:uuid}', [UserProfileController::class, 'downloadCertificate'])->name('user_profile.certificate');
        });
    });
    // advertisement pages
    Route::get('/event_list/{id}', [AdvertisementPageController::class, 'index'])->name('advertisement_pages.index');
    Route::get('/page-view/{slug}/{page_uuid}', [AdvertisementPageController::class, 'show'])->name('advertisement_pages.show');

    Route::post('/schools/{school}/reviews', [ReviewController::class, 'store'])->name('website.school.reviews.store');


    Route::post('/contact-inquiry', [ContactInquiryController::class, 'store'])->name('contact.inquiry.store');

    Route::middleware(['auth', 'verified', 'role:super-admin|school-admin'])->group(function () {

        // Admin routes 
        Route::get('school-admin/inquiries', [ContactInquiryController::class, 'index'])->name('admin.inquiries.index');
        Route::get('school-admin/inquiries/{inquiry}', [ContactInquiryController::class, 'show'])->name('admin.inquiries.show');
        Route::patch('school-admin/inquiries/{inquiry}/status', [ContactInquiryController::class, 'updateStatus'])->name('admin.inquiries.updateStatus');
        Route::post('school-admin/inquiries/{inquiry}/assign', [ContactInquiryController::class, 'assign'])->name('admin.inquiries.assign');
        Route::get('school-admin/inquiries/stats', [ContactInquiryController::class, 'getStats'])->name('admin.inquiries.stats');
        // Admin Review Routes
        Route::get('dashboard/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
        Route::get('dashboard/reviews/create', [App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
        Route::post('dashboard/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
        Route::get('dashboard/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
        Route::get('dashboard/reviews/{review}/edit', [App\Http\Controllers\ReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('dashboard/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('dashboard/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

        // Additional routes
        Route::post('dashboard/reviews/{review}/update-status', [App\Http\Controllers\ReviewController::class, 'updateStatus'])->name('reviews.update-status');
        Route::post('dashboard/reviews/bulk-action', [App\Http\Controllers\ReviewController::class, 'bulkAction'])->name('reviews.bulk-action');
        Route::get('dashboard/reviews/get-branches', [App\Http\Controllers\ReviewController::class, 'getBranches'])->name('reviews.get-branches');


        // Branch Image Management Routes
        Route::prefix('schools/{school}/branches/{branch}')->group(function () {
            Route::get('/images', [BranchController::class, 'imagesIndex'])->name('schools.branches.images.index');
            Route::post('/images', [BranchController::class, 'storeImages'])->name('schools.branches.images.store');
            Route::delete('/images/{image}', [BranchController::class, 'deleteImage'])->name('schools.branches.images.destroy');
            Route::put('/images/{image}', [BranchController::class, 'updateImage'])->name('schools.branches.images.update');
            Route::post('/images/reorder', [BranchController::class, 'reorderImages'])->name('schools.branches.images.reorder');
            Route::get('/images/stats', [BranchController::class, 'getImageStats'])->name('schools.branches.getImageStats');
        });


        // In routes/web.php
        Route::prefix('dashboard')->middleware(['auth', 'role:super-admin'])->group(function () {
            // Dashboard
            Route::get('/mcq-dashboard', [McqDashboardController::class, 'index'])->name('mcq.dashboard');
            Route::get('/mcq-stats', [McqDashboardController::class, 'getStats'])->name('mcq.stats');

            // Test Types
            Route::resource('test-types', TestTypeController::class);
            Route::post('test-types/bulk-action', [TestTypeController::class, 'bulkAction'])->name('test-types.bulk.action');
            Route::post('test-types/update-sort', [TestTypeController::class, 'updateSort'])->name('test-types.update.sort');

            // Subjects
            Route::resource('subjects', SubjectController::class);
            Route::post('subjects/bulk-action', [SubjectController::class, 'bulkAction'])->name('subjects.bulk.action');
            Route::post('subjects/update-sort', [SubjectController::class, 'updateSort'])->name('subjects.update.sort');

            // Topics
            Route::resource('topics', TopicController::class);
            Route::post('topics/bulk-action', [TopicController::class, 'bulkAction'])->name('topics.bulk.action');
            Route::post('topics/update-sort', [TopicController::class, 'updateSort'])->name('topics.update.sort');

            // MCQs
            Route::post('mcqs/bulk-action', [McqController::class, 'bulkAction'])->name('mcqs.bulk.action');
            Route::post('mcqs/{mcq}/verify', [McqController::class, 'verify'])->name('mcqs.verify');
            Route::post('mcqs/{mcq}/unverify', [McqController::class, 'unverify'])->name('mcqs.unverify');
            Route::get('mcqs/get-topics', [McqController::class, 'getTopicsBySubject'])->name('mcqs.get-topics');
            Route::get('mcqs/get-test-types', [McqController::class, 'getTestTypesBySubject'])->name('mcqs.get-test-types');

            // MCQs - Bulk Import
            Route::get('mcqs/bulk-import/template', [McqController::class, 'downloadBulkImportTemplate'])->name('mcqs.bulk-import.template');
            Route::post('mcqs/bulk-import/preview', [McqController::class, 'previewBulkImport'])->name('mcqs.bulk-import.preview');
            Route::post('mcqs/bulk-import', [McqController::class, 'storeBulkImport'])->name('mcqs.bulk-import.store');

            // MCQs - Smart Export Template (pre-filled with selected subject/topic/test types)
            Route::get('mcqs/export-template', [McqController::class, 'exportTemplate'])->name('mcqs.exportTemplate');

            // MCQs - Live search (JSON; must be before resource so "search" is not captured as {mcq})
            Route::get('mcqs/search', [McqController::class, 'searchLive'])->name('mcqs.search');

            Route::resource('mcqs', McqController::class);

            // Mock Tests
            Route::post('mock-tests/bulk-action', [MockTestController::class, 'bulkAction'])->name('mock-tests.bulk.action');
            Route::get('mock-tests/{mockTest}/add-questions', [MockTestController::class, 'addQuestions'])->name('mock-tests.add-questions');
            Route::post('mock-tests/{mockTest}/add-question', [MockTestController::class, 'addQuestion'])->name('mock-tests.add-question');
            Route::delete('mock-tests/{mockTest}/remove-question/{mcq}', [MockTestController::class, 'removeQuestion'])->name('mock-tests.remove-question');
            Route::post('mock-tests/{mockTest}/update-question-order', [MockTestController::class, 'updateQuestionOrder'])->name('mock-tests.update-question-order');
            Route::post('mock-tests/{mockTest}/questions/{question}/update-details', [MockTestController::class, 'updateQuestionDetails'])->name('mock-tests.update-question-details');
            Route::post('mock-tests/{mockTest}/bulk-add-questions', [MockTestController::class, 'bulkAddQuestions'])->name('mock-tests.bulk-add-questions');
            Route::get('mock-tests/get-mcqs/selection', [MockTestController::class, 'getMcqsForSelection'])->name('mock-tests.get-mcqs');
            Route::get('mock-tests/{mockTest}/preview', [MockTestController::class, 'preview'])->name('mock-test.preview');
            Route::resource('mock-tests', MockTestController::class);

            // user-test-attemts
            Route::get('mock-tests/user-test-attempts', [MockTestController::class, 'user-test-attempts'])->name('user-test-attempts.index');
            // Book Categories
            Route::resource('book-categories', BookCategoryController::class);

            // Books
            Route::resource('books', BookController::class);

            // Study Materials
            Route::resource('study-materials', StudyMaterialController::class);
        });

        // School Admin Routes
            Route::prefix('school')->name('school.')->middleware(['auth', 'role:school-admin'])->group(function () {
            Route::get('/mcqs', [SchoolMcqController::class, 'index'])->name('mcqs.index');
            Route::get('/study-materials', [SchoolStudyMaterialController::class, 'index'])->name('study-materials.index');
        });
    });


    // Notification routes
    Route::middleware(['auth'])->group(function () {
        Route::get('admin/inquiries/notification-count', [ContactInquiryController::class, 'getNotificationCount'])->name('admin.inquiries.notification-count');
        Route::post('admin/inquiries/{inquiry}/mark-read', [ContactInquiryController::class, 'markAsRead'])->name('admin.inquiries.mark-read');
    });

    Route::get('/announcement/{announcement}', [WebsiteAnnouncementController::class, 'show'])->name('website.announcements.show');
    Route::post('announcements/{uuid}/comments', [WebsiteAnnouncementController::class, 'storeComment'])->name('announcements.comments.store');

    // Route::post('announcements/{uuid}/comments', [AnnouncementController::class, 'storeComment'])
    //     ->name('announcements.comments.store');

    // Blog module removed from the public website (2026-09-10). The Blog admin/
    // dashboard system and the database are untouched; these URLs were publicly
    // indexed, so they intentionally return 410 Gone instead of a generic
    // redirect/404. Comment submission and reading-time tracking (POST, not
    // indexed) are removed entirely.
    Route::get('blog/', function () {
        abort(410, 'The blog has been permanently removed.');
    })->name('website.blog.index');
    Route::get('blog/{slug}', function () {
        abort(410, 'The blog has been permanently removed.');
    })->name('website.blog.show');
    Route::get('blog/category/{slug}', function () {
        abort(410, 'The blog has been permanently removed.');
    })->name('website.blog.category');
    Route::get('blog/tag/{tag}', function () {
        abort(410, 'The blog has been permanently removed.');
    })->name('website.blog.tag');

    // Shop/Store module removed from the public website (2026-09-10). The dashboard
    // Shop system and the database are untouched; these URLs were publicly indexed,
    // so they intentionally return 410 Gone instead of a generic redirect/404.
    Route::get('shop/', function () {
        abort(410, 'The shop section has been permanently removed.');
    })->name('website.shop.index');
    Route::get('shop/{uuid}', function () {
        abort(410, 'The shop section has been permanently removed.');
    })->name('website.shop.show');
    Route::get('products/', function () {
        abort(410, 'The shop section has been permanently removed.');
    })->name('website.stationary.index');

    // Comments
    // Route::post('dashboard/videos/{video}/comments', [VideoCommentController::class, 'store'])->name('admin.videos.comments.store');
    // Route::post('dashboard/videos/comments/{comment}/like', [VideoCommentController::class, 'like'])->name('admin.videos.comments.like');
    // Route::delete('dashboard/videos/comments/{comment}', [VideoCommentController::class, 'destroy'])->name('admin.videos.comments.destroy');

    // Reactions
    Route::post('dashboard/videos/{video}/reactions', [VideoReactionController::class, 'store'])->name('videos.reactions.store');

    // Cart, Checkout and public Order routes removed along with the Shop module
    // (2026-09-10). Not publicly indexed (session/transactional pages), so a plain
    // 404 is sufficient — no redirect/410 handling needed.

    Route::view('privacy', 'website.privacy')->name('website.privacy');
    Route::view('terms', 'website.terms')->name('website.terms');


    Route::get('videos/', [VideoWebsiteController::class, 'index'])->name('website.videos.index');
    Route::get('videos/category/{slug}', [VideoWebsiteController::class, 'category'])->name('website.videos.category');
    Route::get('videos/{slug}', [VideoWebsiteController::class, 'show'])->name('website.videos.show');
    Route::post('videos/{video}/watch-time', [VideoWebsiteController::class, 'trackWatchTime'])
        ->middleware('throttle:120,1')
        ->name('website.videos.watch-time');

    // Comments
    Route::post('videos/{video}/comments', [VideoWebsiteController::class, 'storeComment'])->name('website.videos.comments.store');
    // GET fallback so search-engine crawlers hitting this POST-only action URL get a
    // 302 redirect instead of a 405/500 (the source of "Blocked due to other 4xx/5xx"
    // entries in Google Search Console).
    Route::get('videos/{video}/comments', function () {
        return redirect()->route('website.videos.index');
    })->name('website.videos.comments.redirect');



    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');


    // Public MCQ/Quiz module removed (Step 1, 2026-09-10). The MCQ admin/
    // dashboard system and the database are untouched. Content pages that were
    // publicly browsable/indexed return 410 Gone; session/transactional
    // endpoints (attempts, answer submission, results) are removed entirely
    // (404) since they were never indexed.
    Route::prefix('quiz')->name('website.mcqs.')->group(function () {
        $gone = function () {
            abort(410, 'The MCQ/quiz section has been permanently removed.');
        };

        Route::get('/', $gone)->name('index');
        Route::get('/test/{test_type}', $gone)->name('test-type');
        Route::get('/subject/{subject}', $gone)->name('subject');
        Route::get('/subject/{subject}/topic/{topic}', $gone)->name('topic');
        Route::get('/test/{test_type}/subject/{subject}', $gone)->name('subject-by-test-type');
        Route::get('/practice/{mcq}', $gone)->name('practice');
        Route::get('/mock-tests', $gone)->name('mock-tests');
        Route::get('/mock-tests/{mock_test}', $gone)->name('mock-test-detail');
    });

    require __DIR__ . '/auth.php';
});
