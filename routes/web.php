<?php

use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FaqController as PublicFaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController as PublicProjectController;
use App\Http\Controllers\ServiceController as PublicServiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/services', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [PublicServiceController::class, 'show'])->name('services.show');

Route::get('/portfolio', [PublicProjectController::class, 'index'])->name('projects.index');
Route::get('/portfolio/{slug}', [PublicProjectController::class, 'show'])->name('projects.show');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/faqs', [PublicFaqController::class, 'index'])->name('faqs.index');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

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
    Route::get('/{any?}', [DashboardController::class, 'index'])->where('any', 'dashboard')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('change-password');
    Route::put('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::resource('services', ServiceController::class)->except(['show']);
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
});
