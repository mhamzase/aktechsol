<?php

namespace App\Providers;

use App\Models\SiteAsset;
use App\Settings\SiteSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share logo/favicon with guest and admin layouts
        View::composer(['layouts.guest', 'admin.layouts.app', 'layouts.app'], function ($view) {
            $siteAsset = SiteAsset::first();
            $view->with('logoUrl', $siteAsset ? $siteAsset->getLogoUrl() : null);
            $view->with('faviconUrl', $siteAsset ? $siteAsset->getFaviconUrl() : null);
        });

        // Share site settings with ALL views
        View::composer('*', function ($view) {
            $view->with('siteSettings', app(SiteSettings::class));
        });

        // Optionally share unread contact messages count with admin sidebar
        View::composer('admin.partials.sidebar', function ($view) {
            $unreadContactMessages = \App\Models\ContactMessage::where('status', 'new')->count();
            $view->with('unreadContactMessages', $unreadContactMessages);
        });
    }
}
