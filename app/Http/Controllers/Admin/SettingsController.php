<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSiteSettingsRequest;
use App\Models\SiteAsset;
use App\Settings\SiteSettings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(SiteSettings $settings)
    {
        $siteAsset = SiteAsset::firstOrCreate(['name' => 'default']);
        return view('admin.settings', [
            'settings' => $settings,
            'logoUrl' => $siteAsset->getLogoUrl(),
            'faviconUrl' => $siteAsset->getFaviconUrl(),
        ]);
    }

    public function update(UpdateSiteSettingsRequest $request, SiteSettings $settings)
    {
        // Update text settings
        $settings->fill($request->validated());
        $settings->save();

        // Handle logo upload
        $siteAsset = SiteAsset::firstOrCreate(['name' => 'default']);
        if ($request->hasFile('logo')) {
            $siteAsset->clearMediaCollection('logo');
            $siteAsset->addMediaFromRequest('logo')->toMediaCollection('logo');
        }
        if ($request->hasFile('favicon')) {
             $siteAsset->clearMediaCollection('favicon');
            $siteAsset->addMediaFromRequest('favicon')->toMediaCollection('favicon');
        }

        return redirect()->route('admin.settings')
                         ->with('success', 'Settings updated successfully.');
    }
}
