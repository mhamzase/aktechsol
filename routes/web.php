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
use App\Http\Controllers\ContactController;
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

    // Existing content modules
    Route::resource('services', ServiceController::class);
    Route::post('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('services.toggle-status');
    Route::resource('service-categories', ServiceCategoryController::class)->except(['show']);
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('blog-categories', BlogCategoryController::class)->except(['show']);
    Route::resource('blog-posts', BlogPostController::class)->except(['show']);
    Route::resource('faqs', FaqController::class)->except(['show']);

    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::put('contact-messages/{contactMessage}/status', [ContactMessageController::class, 'updateStatus'])->name('contact-messages.update-status');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    Route::get('newsletter-subscribers', [NewsletterSubscriberController::class, 'index'])->name('newsletter-subscribers.index');
    Route::delete('newsletter-subscribers/{newsletterSubscriber}', [NewsletterSubscriberController::class, 'destroy'])->name('newsletter-subscribers.destroy');
    Route::get('media', [MediaController::class, 'index'])->name('media.index');
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
    $soon('quotes', 'Quote Requests', 'Phase 2', 'quotes.index', 'manage quotes');
    $soon('proposals', 'Proposals', 'Phase 2', 'proposals.index', 'manage proposals');
    $soon('client-projects', 'Client Projects', 'Phase 3', 'client-projects.index', 'manage projects');
    $soon('invoices', 'Invoices', 'Phase 4', 'invoices.index', 'manage invoices');
    $soon('reviews', 'Reviews', 'Phase 3', 'reviews.index', 'manage reviews');
    $soon('chatbot', 'Chatbot', 'Phase 5', 'chatbot.index', 'manage chatbot');
    $soon('demo-bookings', 'Demo Bookings', 'Phase 6', 'demo-bookings.index', 'manage demo bookings');
    $soon('job-applications', 'Job Applications', 'Phase 6', 'job-applications.index', 'manage job applications');
    $soon('packages', 'Packages', 'Phase 6', 'packages.index', 'manage packages');
    $soon('jobs', 'Jobs', 'Phase 6', 'jobs.index', 'manage jobs');
});
