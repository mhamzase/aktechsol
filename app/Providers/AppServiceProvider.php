<?php

namespace App\Providers;

use App\Models\SiteAsset;
use App\Settings\SiteSettings;
use App\Support\AdminNav;
use App\Support\MailConfig;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share logo/favicon with guest and admin layouts
        View::composer(['layouts.guest', 'admin.layouts.app', 'layouts.app'], function ($view) {
            try {
                $siteAsset = SiteAsset::first();
                $view->with('logoUrl', $siteAsset ? $siteAsset->getLogoUrl() : null);
                $view->with('faviconUrl', $siteAsset ? $siteAsset->getFaviconUrl() : null);
            } catch (\Throwable $e) {
                $view->with('logoUrl', null)->with('faviconUrl', null);
            }
        });

        // Share site settings with ALL views
        View::composer('*', function ($view) {
            try {
                $view->with('siteSettings', app(SiteSettings::class));
            } catch (\Throwable $e) {
                $view->with('siteSettings', null);
            }
        });

        // Admin shell: sidebar badges + topbar notifications (once per request)
        View::composer('admin.layouts.app', function ($view) {
            $view->with('navBadges', AdminNav::badges());
            $view->with('navNotifications', AdminNav::latestNotifications());
        });

        // Apply admin-configured SMTP settings at runtime (if set)
        MailConfig::apply();
    }
}
