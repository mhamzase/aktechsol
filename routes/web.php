<?php

use App\Http\Controllers\Admin\ChangePasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController as PublicServiceController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/services', [PublicServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [PublicServiceController::class, 'show'])->name('services.show');

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('change-password');
    Route::put('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');

    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    Route::resource('services', ServiceController::class)->except(['show']);
});
