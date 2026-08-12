<?php

namespace App\Providers;

use App\Models\SiteAsset;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer(['layouts.guest', 'admin.layouts.app', 'layouts.app'], function ($view) {
            $siteAsset = SiteAsset::first();
            $view->with('logoUrl', $siteAsset ? $siteAsset->getLogoUrl() : null);
            $view->with('faviconUrl', $siteAsset ? $siteAsset->getFaviconUrl() : null);
        });

        // Also share all SiteSettings with the public layout
        View::composer('layouts.app', function ($view) {
            $settings = app(\App\Settings\SiteSettings::class);
            $view->with('siteSettings', $settings);
        });
    }
}
