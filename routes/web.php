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
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/public/en', '/', 301);
Route::redirect('/en', '/', 301);
Route::redirect('/en/', '/', 301);
Route::redirect('/public', '/', 301);

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

use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\SchoolMcqController;
use App\Http\Controllers\SchoolStudyMaterialController;
use App\Http\Controllers\ShopSchoolAssociationController;
use App\Http\Controllers\StudyMaterialController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TestTypeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoCategoryController;
use App\Http\Controllers\VideoCommentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoReactionController;
use App\Http\Controllers\Website\BlogCommentController;
use App\Http\Controllers\Website\VideoWebsiteController;
use App\Http\Controllers\Website\WebsiteCartController;
use App\Http\Controllers\Website\WebsiteCheckoutController;
use App\Http\Controllers\Website\WebsiteOrderController;
use App\Http\Controllers\Website\WebsiteShopController;
use App\Http\Controllers\Website\WebsiteProductsController;
use App\Http\Controllers\Website\WebsiteModalController;
use App\Http\Controllers\Website\TestimonialController;
use App\Http\Controllers\Website\WebsiteMcqController;
use App\Http\Controllers\Website\WebsiteMockMcqController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {

    Route::middleware(['auth', 'verified', 'role:super-admin|school-admin|shop-owner'])->group(function () {

        Route::get('/dashboard', [DashboardControlle::class, 'index'])->name('dashboard');


        // Add the custom create route separately
        Route::resource('schools', SchoolController::class);
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

        // // Dashboard Shop Routes
        // // Route::resource('shops', ShopController::class);
        // Route::get('dashboard/shops', [ShopController::class, 'index'])->name('shops.index');
        // Route::get('dashboard/shops/create', [ShopController::class, 'create'])->name('shops.create');
        // Route::post('dashboard/shops', [ShopController::class, 'store'])->name('shops.store');
        // Route::get('dashboard/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');
        // Route::get('dashboard/shops/{shop}/edit', [ShopController::class, 'edit'])->name('shops.edit');
        // Route::put('dashboard/shops/{shop}', [ShopController::class, 'update'])->name('shops.update');
        // Route::patch('dashboard/shops/{shop}', [ShopController::class, 'update'])->name('shops.update');
        // Route::delete('dashboard/shops/{shop}', [ShopController::class, 'destroy'])->name('shops.destroy');
        // Route::post('shops/{shop}/associate-school', [ShopController::class, 'associateSchool'])->name('shops.associate-school');
        // Route::get('shops/{shop}/associations', [ShopController::class, 'getAssociations'])->name('shops.associations');

        // Dashboard Product Routes
        // Route::resource('products', ProductController::class);
        Route::get('dashboard/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('dashboard/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('dashboard/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('dashboard/products/{product}', [ProductController::class, 'show'])->name('products.show');
        Route::get('dashboard/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('dashboard/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::patch('dashboard/products/{product}', [ProductController::class, 'update'])->name('products.update.patch');
        Route::delete('dashboard/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');

        // Product Category Routes
        Route::resource('product-categories', ProductCategoryController::class);

        // Shop School Association Routes
        Route::resource('shop-school-associations', ShopSchoolAssociationController::class)->except(['store']);
        Route::post('shop-school-associations/{association}/approve', [ShopSchoolAssociationController::class, 'approve'])->name('shop-school-associations.approve');
        Route::post('shop-school-associations/{association}/reject', [ShopSchoolAssociationController::class, 'reject'])->name('shop-school-associations.reject');

        Route::resource('announcements', AnnouncementController::class);

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


        // access to update status of school
        Route::put('/shop-school-associations/{association}/status', [ShopSchoolAssociationController::class, 'updateStatus'])->name('shop-school-associations.update-status');

        Route::get('dashboard/orders/', [OrderController::class, 'index'])->name('dashboard.orders.index');
        Route::get('dashboard/orders/{order}', [OrderController::class, 'show'])->name('dashboard.orders.show');
        Route::post('dashboard/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('dashboard.orders.update-status');
        Route::post('dashboard/orders/{order}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('dashboard.orders.update-payment-status');
        Route::post('dashboard/orders/{order}/update-shipping', [OrderController::class, 'updateShippingInfo'])->name('dashboard.orders.update-shipping');
        Route::post('dashboard/orders/{order}/add-notes', [OrderController::class, 'addAdminNotes'])->name('dashboard.orders.add-notes');
        Route::get('dashboard/orders/export/orders', [OrderController::class, 'exportOrders'])->name('dashboard.orders.export');


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
    Route::get('/search', [HomeController::class, 'search'])->name('search.schools');

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

    Route::middleware(['auth', 'verified', 'role:super-admin|shop-owner'])->group(function () {

        // Route::resource('shops', ShopController::class);
        Route::get('dashboard/shops', [ShopController::class, 'index'])->name('shops.index');
        Route::get('dashboard/shops/create', [ShopController::class, 'create'])->name('shops.create');
        Route::post('dashboard/shops', [ShopController::class, 'store'])->name('shops.store');
        Route::get('dashboard/shops/{shop}', [ShopController::class, 'show'])->name('shops.show');
        Route::get('dashboard/shops/{shop}/edit', [ShopController::class, 'edit'])->name('shops.edit');
        Route::put('dashboard/shops/{shop}', [ShopController::class, 'update'])->name('shops.update');
        Route::patch('dashboard/shops/{shop}', [ShopController::class, 'update'])->name('shops.update');
        Route::delete('dashboard/shops/{shop}', [ShopController::class, 'destroy'])->name('shops.destroy');
        Route::post('shops/{shop}/associate-school', [ShopController::class, 'associateSchool'])->name('shops.associate-school');
        Route::get('shops/{shop}/associations', [ShopController::class, 'getAssociations'])->name('shops.associations');
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
        });
    });
    // advertisement pages
    Route::get('/event_list/{id}', [AdvertisementPageController::class, 'index'])->name('advertisement_pages.index');
    Route::get('/page-view/{slug}/{page_uuid}', [AdvertisementPageController::class, 'show'])->name('advertisement_pages.show');

    Route::post('/schools/{school}/reviews', [ReviewController::class, 'store'])->name('reviews.store');


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
        Route::prefix('school')->middleware(['auth', 'role:school-admin'])->group(function () {
            Route::get('/mcqs', [SchoolMcqController::class, 'index'])->name('school.mcqs.index');
            Route::get('/study-materials', [SchoolStudyMaterialController::class, 'index'])->name('school.study-materials.index');
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

    // Blog Routes
    Route::get('blog/', [WebsiteBlogPostController::class, 'index'])->name('website.blog.index');
    Route::get('blog/{slug}', [WebsiteBlogPostController::class, 'show'])->name('website.blog.show');
    Route::get('blog/category/{slug}', [WebsiteBlogPostController::class, 'category'])->name('website.blog.category');
    Route::get('blog/tag/{tag}', [WebsiteBlogPostController::class, 'tag'])->name('website.blog.tag');
    Route::post('blog/{post}/comment', [\App\Http\Controllers\Website\BlogCommentController::class, 'store'])->name('website.blog.comment.store');
    Route::post('blog/{post}/reading-time', [WebsiteBlogPostController::class, 'trackReadingTime'])
        ->middleware('throttle:60,1')
        ->name('website.blog.reading-time');

    Route::get('shop/', [WebsiteShopController::class, 'index'])->name('website.shop.index');
    Route::get('shop/{uuid}', [WebsiteShopController::class, 'show'])->name('website.shop.show');

    Route::get('products/', [WebsiteProductsController::class, 'index'])->name('website.stationary.index');

    // Comments
    // Route::post('dashboard/videos/{video}/comments', [VideoCommentController::class, 'store'])->name('admin.videos.comments.store');
    // Route::post('dashboard/videos/comments/{comment}/like', [VideoCommentController::class, 'like'])->name('admin.videos.comments.like');
    // Route::delete('dashboard/videos/comments/{comment}', [VideoCommentController::class, 'destroy'])->name('admin.videos.comments.destroy');

    // Reactions
    Route::post('dashboard/videos/{video}/reactions', [VideoReactionController::class, 'store'])->name('videos.reactions.store');

    // Cart and Checkout Routes
    Route::get('/cart', [WebsiteCartController::class, 'index'])->name('website.cart');
    Route::post('/cart/add', [WebsiteCartController::class, 'addToCart'])->name('website.cart.add');
    Route::post('/cart/update', [WebsiteCartController::class, 'updateCart'])->name('website.cart.update');
    Route::post('/cart/remove', [WebsiteCartController::class, 'removeFromCart'])->name('website.cart.remove');
    Route::post('/cart/clear', [WebsiteCartController::class, 'clearCart'])->name('website.cart.clear');
    Route::get('/cart/count', [WebsiteCartController::class, 'getCartCount'])->name('website.cart.count');


    Route::get('/checkout', [WebsiteCheckoutController::class, 'index'])->name('website.checkout');
    Route::post('/checkout/process', [WebsiteCheckoutController::class, 'process'])->name('website.checkout.process');
    Route::post('/checkout/apply-coupon', [WebsiteCheckoutController::class, 'applyCoupon'])->name('website.checkout.apply-coupon');
    Route::post('/checkout/remove-coupon', [WebsiteCheckoutController::class, 'removeCoupon'])->name('website.checkout.remove-coupon');


    Route::get('orders/confirmation/{order}', [WebsiteOrderController::class, 'confirmation'])->name('website.order.confirmation');
    Route::get('orders/track', [WebsiteOrderController::class, 'track'])->name('website.order.track');
    Route::get('orders/{order}', [WebsiteOrderController::class, 'show'])->name('website.order.show');


    Route::view('privacy', 'website.privacy')->name('website.privacy');
    Route::view('terms', 'website.terms')->name('website.terms');


    Route::get('videos/', [VideoWebsiteController::class, 'index'])->name('website.videos.index');
    Route::get('videos/category/{slug}', [VideoWebsiteController::class, 'category'])->name('website.videos.category');
    Route::get('videos/{slug}', [VideoWebsiteController::class, 'show'])->name('website.videos.show');

    // Comments
    Route::post('videos/{video}/comments', [VideoWebsiteController::class, 'storeComment'])->name('website.videos.comments.store');



    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');


    // Modal routes
    Route::get('/modal/product/{product}', [WebsiteModalController::class, 'productModal'])->name('website.modal.product');


    // MCQs Routes
Route::prefix('mcq')->name('website.mcqs.')->group(function () {

    // ✅ STATIC ROUTES FIRST
    Route::get('/mock-tests', [WebsiteMockMcqController::class, 'mockTests'])
        ->name('mock-tests');

    Route::get('/mock-tests/{mock_test:slug}', [WebsiteMockMcqController::class, 'mockTestDetail'])
        ->name('mock-test-detail');

    Route::get('/mock-tests/{mock_test:slug}/start', [WebsiteMockMcqController::class, 'startMockTest'])
        ->name('start-mock-test');

    Route::post('/mock-tests/{mock_test:slug}/submit', [WebsiteMockMcqController::class, 'submitMockTest'])
        ->name('submit-mock-test');

    // ✅ TEST ATTEMPT ROUTES - Place these BEFORE dynamic routes
    Route::get('/take/{attempt:uuid}', [WebsiteMockMcqController::class, 'takeTest'])
        ->name('take-test');

    Route::post('/attempt/{attempt:uuid}/save', [WebsiteMockMcqController::class, 'saveAnswer'])
        ->name('save-answer');

    // Route::post('/attempt/{attempt:uuid}/submit', [WebsiteMockMcqController::class, 'submitTest'])
    //     ->name('submit-test');

    Route::get('/test-attempts/{attempt:uuid}', [WebsiteMockMcqController::class, 'testResult'])
        ->name('test-result');

    // ✅ TOPIC TEST RESULTS ROUTE - ADD THIS NEW ROUTE
    Route::get('/topic/{topic:slug}/results', [WebsiteMcqController::class, 'topicTestResults'])
        ->name('test-results');

    Route::get('/test/{test_type:slug}/subject/{subject:slug}/results', [WebsiteMcqController::class, 'subjectTestResults'])
    ->name('subject-results');

    // ✅ THEN DYNAMIC ROUTES
    Route::get('/', [WebsiteMcqController::class, 'index'])->name('index');

    // Test Type routes
    Route::get('/test/{test_type:slug}', [WebsiteMcqController::class, 'testType'])->name('test-type');

    // Subject routes
    Route::get('/subject/{subject:slug}', [WebsiteMcqController::class, 'subject'])->name('subject');

    // Topic routes
    Route::get('/subject/{subject:slug}/topic/{topic:slug}', [WebsiteMcqController::class, 'topic'])->name('topic');

    Route::post('/mcqs/submit-topic-test', [WebsiteMcqController::class, 'submitTopicTest'])->name('submit-topic-test');
    Route::post('/submit-test', [WebsiteMcqController::class, 'submitTest'])->name('submit-test');
    // Test Type + Subject routes  
    Route::get('/test/{test_type:slug}/subject/{subject:slug}', [WebsiteMcqController::class, 'subjectByTestType'])->name('subject-by-test-type');

    // Practice routes
    Route::get('/practice/{mcq:uuid}', [WebsiteMcqController::class, 'practice'])->name('practice');
    Route::post('/practice/{mcq:uuid}/check', [WebsiteMcqController::class, 'checkAnswer'])
        ->middleware('noindex_robots')
        ->name('check-answer');
});

    require __DIR__ . '/auth.php';
});
