<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\ComingSoonController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TwoFactorController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DemoBookingController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ClientProposalController;
use App\Http\Controllers\FaqController as PublicFaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ProjectController as PublicProjectController;
use App\Http\Controllers\ServiceController as PublicServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'about'])->name('about');

Route::get('/services', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [PublicServiceController::class, 'show'])->name('services.show');

Route::get('/portfolio', [PublicProjectController::class, 'index'])->name('projects.index');
Route::get('/portfolio/{slug}', [PublicProjectController::class, 'show'])->name('projects.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/faqs', [PublicFaqController::class, 'index'])->name('faqs.index');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/get-a-quote', [QuoteController::class, 'create'])->name('quote.create');
Route::post('/get-a-quote', [QuoteController::class, 'store'])->name('quote.store');

Route::get('/book-a-demo', [DemoBookingController::class, 'create'])->name('demo.create');
Route::post('/book-a-demo', [DemoBookingController::class, 'store'])->middleware('throttle:10,1')->name('demo.store');

Route::get('/careers', [CareerController::class, 'index'])->name('careers.index');
Route::get('/careers/{slug}', [CareerController::class, 'show'])->name('careers.show');
Route::post('/careers/{slug}/apply', [CareerController::class, 'apply'])->middleware('throttle:10,1')->name('careers.apply');

Route::post('/chatbot/message', [ChatbotController::class, 'message'])->name('chatbot.message');

Route::get('/proposal/{token}', [ClientProposalController::class, 'show'])->name('proposals.show');
Route::post('/proposal/{token}/respond', [ClientProposalController::class, 'respond'])->name('proposals.respond');
Route::get('/proposal/{token}/pdf', [ClientProposalController::class, 'pdf'])->name('proposals.pdf.download');

// Client project tracking (Phase 3).
Route::get('/project/{token}', [\App\Http\Controllers\ClientProjectTrackingController::class, 'show'])->name('projects.track');

// Client invoice view + payment proof (Phase 4).
Route::get('/invoice/{token}', [\App\Http\Controllers\ClientInvoiceController::class, 'show'])->name('invoices.show');
Route::post('/invoice/{token}/proof', [\App\Http\Controllers\ClientInvoiceController::class, 'submitProof'])->name('invoices.proof');

// Client review form (Phase 3).
Route::get('/review/{token}', [\App\Http\Controllers\ClientReviewController::class, 'showForm'])->name('reviews.form');
Route::post('/review/{token}', [\App\Http\Controllers\ClientReviewController::class, 'submit'])->name('reviews.submit');

Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacy-policy');

Route::get('/terms-conditions', function () {
    return view('terms-conditions');
})->name('terms-conditions');


Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/robots.txt', function () {
    $content = "User-agent: *\n";
    $content .= "Allow: /\n";
    $content .= "Sitemap: " . url('/sitemap.xml') . "\n";
    return response($content, 200)->header('Content-Type', 'text/plain');
})->name('robots');

/*
|--------------------------------------------------------------------------
| Authentication Routes (provided by Laravel Fortify)
|--------------------------------------------------------------------------
| Fortify automatically registers:
| - /login (GET, POST)
| - /logout (POST)
| - /forgot-password (GET, POST)
| - /reset-password/{token} (GET)
| - /reset-password (POST)
|--------------------------------------------------------------------------
| Ensure FortifyServiceProvider is registered in config/app.php
*/

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications center
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    Route::post('notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'avatar'])->name('profile.avatar');

    // Two-factor authentication (AJAX, Fortify confirm mode)
    Route::prefix('/profile/two-factor')->name('profile.2fa.')->group(function () {
        Route::get('/status', [TwoFactorController::class, 'status'])->name('status');
        Route::post('/enable', [TwoFactorController::class, 'enable'])->name('enable');
        Route::post('/confirm', [TwoFactorController::class, 'confirm'])->name('confirm');
        Route::delete('/disable', [TwoFactorController::class, 'disable'])->name('disable');
        Route::post('/recovery-codes/regenerate', [TwoFactorController::class, 'regenerateCodes'])->name('codes.regenerate');
    });

    // UI component kit demo (dev/QA only — showcases every x-ui.* component)
    Route::get('/ui-demo', function () {
        return view('admin.ui-demo');
    })->name('ui-demo');

    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('change-password');
    Route::put('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');

    // Settings — tabbed one-page (tailor-app style, no refresh)
    Route::get('/settings/{tab?}', [SettingsController::class, 'edit'])
        ->middleware('can:manage settings')->name('settings');
    Route::put('/settings/{tab?}', [SettingsController::class, 'update'])
        ->middleware('can:manage settings')->name('settings.update');
    Route::post('/settings/test-email', [SettingsController::class, 'testEmail'])
        ->middleware('can:manage settings')->name('settings.test-email');

    // Users & roles
    Route::resource('users', UserController::class)
        ->except(['show'])->middleware('can:manage users');
    // User-wise direct permissions (Team Members). Stored on the user, never on the role.
    Route::get('users/{user}/permissions', [UserPermissionController::class, 'edit'])
        ->middleware('can:manage users')->name('users.permissions.edit');
    Route::put('users/{user}/permissions', [UserPermissionController::class, 'update'])
        ->middleware('can:manage users')->name('users.permissions.update');

    // Legal pages (Privacy / Terms / Policies) — rich text editable
    Route::get('pages', [PageController::class, 'index'])
        ->middleware('can:manage pages')->name('pages.index');
    Route::get('pages/{page}/edit', [PageController::class, 'edit'])
        ->middleware('can:manage pages')->name('pages.edit');
    Route::put('pages/{page}', [PageController::class, 'update'])
        ->middleware('can:manage pages')->name('pages.update');

    // Activity log
    Route::get('activity-log', [ActivityLogController::class, 'index'])
        ->middleware('can:view activity log')->name('activity-log.index');
    Route::get('activity-log/{log}', [ActivityLogController::class, 'show'])
        ->middleware('can:view activity log')->name('activity-log.show');

    // Existing content modules
    Route::resource('services', ServiceController::class);
    Route::post('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    Route::resource('service-categories', ServiceCategoryController::class);
    Route::post('service-categories/{serviceCategory}/toggle-status', [ServiceCategoryController::class, 'toggleStatus'])->name('service-categories.toggle-status');
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/toggle-status', [ProjectController::class, 'toggleStatus'])->name('projects.toggle-status');
    Route::resource('testimonials', TestimonialController::class);
    Route::post('testimonials/{testimonial}/toggle-status', [TestimonialController::class, 'toggleStatus'])->name('testimonials.toggle-status');
    Route::resource('blog-categories', BlogCategoryController::class);
    Route::post('blog-categories/{blogCategory}/toggle-status', [BlogCategoryController::class, 'toggleStatus'])->name('blog-categories.toggle-status');
    Route::resource('blog-posts', BlogPostController::class);
    Route::post('blog-posts/{blogPost}/toggle-status', [BlogPostController::class, 'toggleStatus'])->name('blog-posts.toggle-status');
    Route::resource('faqs', FaqController::class);
    Route::post('faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faqs.toggle-status');

    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::put('contact-messages/{contactMessage}/status', [ContactMessageController::class, 'updateStatus'])->name('contact-messages.update-status');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    Route::get('quotes', [\App\Http\Controllers\Admin\QuoteController::class, 'index'])->name('quotes.index');
    Route::get('quotes/{quote}', [\App\Http\Controllers\Admin\QuoteController::class, 'show'])->name('quotes.show');
    Route::put('quotes/{quote}/status', [\App\Http\Controllers\Admin\QuoteController::class, 'updateStatus'])->name('quotes.update-status');
    Route::delete('quotes/{quote}', [\App\Http\Controllers\Admin\QuoteController::class, 'destroy'])->name('quotes.destroy');

    Route::get('chatbot', [\App\Http\Controllers\Admin\ChatbotController::class, 'index'])->middleware('can:manage chatbot')->name('chatbot.index');
    Route::get('chatbot/{chatbotQuery}', [\App\Http\Controllers\Admin\ChatbotController::class, 'show'])->middleware('can:manage chatbot')->name('chatbot.show');
    Route::put('chatbot/{chatbotQuery}', [\App\Http\Controllers\Admin\ChatbotController::class, 'update'])->middleware('can:manage chatbot')->name('chatbot.update');
    Route::delete('chatbot/{chatbotQuery}', [\App\Http\Controllers\Admin\ChatbotController::class, 'destroy'])->middleware('can:manage chatbot')->name('chatbot.destroy');

    Route::get('newsletter-subscribers', [NewsletterSubscriberController::class, 'index'])->name('newsletter-subscribers.index');
    Route::delete('newsletter-subscribers/{newsletterSubscriber}', [NewsletterSubscriberController::class, 'destroy'])->name('newsletter-subscribers.destroy');
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::get('media/{media}', [MediaController::class, 'show'])->name('media.show');
    Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    /*
    |----------------------------------------------------------------------
    | Phase placeholders — every sidebar link must resolve. Each phase
    | replaces its placeholder with the real module controller.
    |----------------------------------------------------------------------
    */
    $soon = function (string $uri, string $title, string $phase, string $name, string $can) {
        Route::get($uri, fn () => app(ComingSoonController::class)->show($title, $phase))
            ->middleware("can:{$can}")->name($name);
    };
    Route::resource('proposals', \App\Http\Controllers\Admin\ProposalController::class)->middleware('can:manage proposals');
    Route::post('proposals/{proposal}/send', [\App\Http\Controllers\Admin\ProposalController::class, 'send'])->middleware('can:manage proposals')->name('proposals.send');
    Route::get('proposals/{proposal}/pdf', [\App\Http\Controllers\Admin\ProposalController::class, 'pdf'])->middleware('can:manage proposals')->name('proposals.pdf');
    Route::post('proposals/{proposal}/resend', [\App\Http\Controllers\Admin\ProposalController::class, 'resend'])->middleware('can:manage proposals')->name('proposals.resend');
    Route::post('proposals/{proposal}/duplicate', [\App\Http\Controllers\Admin\ProposalController::class, 'duplicate'])->middleware('can:manage proposals')->name('proposals.duplicate');

    // Client projects (Phase 3).
    Route::resource('client-projects', \App\Http\Controllers\Admin\ClientProjectController::class)->middleware('can:manage proposals');
    Route::post('client-projects/{clientProject}/updates', [\App\Http\Controllers\Admin\ClientProjectController::class, 'addUpdate'])->middleware('can:manage proposals')->name('client-projects.updates.store');
    Route::delete('client-projects/{clientProject}/updates/{updateId}', [\App\Http\Controllers\Admin\ClientProjectController::class, 'deleteUpdate'])->middleware('can:manage proposals')->name('client-projects.updates.destroy');
    // Invoices (Phase 4).
    Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class)->middleware('can:manage invoices');
    Route::post('invoices/{invoice}/payments/{payment}/verify', [\App\Http\Controllers\Admin\InvoiceController::class, 'verifyPayment'])->middleware('can:manage invoices')->name('invoices.payments.verify');
    Route::post('invoices/{invoice}/payments/{payment}/reject', [\App\Http\Controllers\Admin\InvoiceController::class, 'rejectPayment'])->middleware('can:manage invoices')->name('invoices.payments.reject');
    Route::post('invoices/{invoice}/payments/manual', [\App\Http\Controllers\Admin\InvoiceController::class, 'addManualPayment'])->middleware('can:manage invoices')->name('invoices.payments.manual');
    Route::post('invoices/{invoice}/mark-paid', [\App\Http\Controllers\Admin\InvoiceController::class, 'markPaid'])->middleware('can:manage invoices')->name('invoices.mark-paid');
    Route::post('invoices/{invoice}/send-email', [\App\Http\Controllers\Admin\InvoiceController::class, 'sendEmail'])->middleware('can:manage invoices')->name('invoices.send-email');
    Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\Admin\InvoiceController::class, 'downloadPdf'])->middleware('can:manage invoices')->name('invoices.pdf');
    // Reviews (Phase 3).
    Route::get('reviews/request-form', [\App\Http\Controllers\Admin\ReviewController::class, 'requestForm'])->middleware('can:manage reviews')->name('reviews.request-form');
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)->only(['index', 'show', 'edit', 'update', 'destroy'])->middleware('can:manage reviews');
    Route::post('reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->middleware('can:manage reviews')->name('reviews.approve');
    Route::post('reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->middleware('can:manage reviews')->name('reviews.reject');
    Route::post('reviews/send-request', [\App\Http\Controllers\Admin\ReviewController::class, 'sendRequest'])->middleware('can:manage reviews')->name('reviews.send-request');
    $soon('chatbot', 'Chatbot', 'Phase 5', 'chatbot.index', 'manage chatbot');
    // Demo Bookings (Phase 6).
    Route::get('demo-bookings', [\App\Http\Controllers\Admin\DemoBookingController::class, 'index'])->middleware('can:manage demo bookings')->name('demo-bookings.index');
    Route::get('demo-bookings/{demoBooking}', [\App\Http\Controllers\Admin\DemoBookingController::class, 'show'])->middleware('can:manage demo bookings')->name('demo-bookings.show');
    Route::put('demo-bookings/{demoBooking}/status', [\App\Http\Controllers\Admin\DemoBookingController::class, 'updateStatus'])->middleware('can:manage demo bookings')->name('demo-bookings.update-status');
    Route::delete('demo-bookings/{demoBooking}', [\App\Http\Controllers\Admin\DemoBookingController::class, 'destroy'])->middleware('can:manage demo bookings')->name('demo-bookings.destroy');
    // Job Applications (Phase 6).
    Route::get('job-applications', [\App\Http\Controllers\Admin\JobApplicationController::class, 'index'])->middleware('can:manage job applications')->name('job-applications.index');
    Route::get('job-applications/{jobApplication}', [\App\Http\Controllers\Admin\JobApplicationController::class, 'show'])->middleware('can:manage job applications')->name('job-applications.show');
    Route::put('job-applications/{jobApplication}/status', [\App\Http\Controllers\Admin\JobApplicationController::class, 'updateStatus'])->middleware('can:manage job applications')->name('job-applications.update-status');
    Route::delete('job-applications/{jobApplication}', [\App\Http\Controllers\Admin\JobApplicationController::class, 'destroy'])->middleware('can:manage job applications')->name('job-applications.destroy');
    $soon('packages', 'Packages', 'Phase 6', 'packages.index', 'manage packages');
    // Jobs (Phase 6).
    Route::resource('jobs', \App\Http\Controllers\Admin\JobController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])->middleware('can:manage jobs');
});
