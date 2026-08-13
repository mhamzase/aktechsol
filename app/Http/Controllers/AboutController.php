<?php

namespace App\Http\Controllers;

use App\Settings\SiteSettings;

class AboutController extends Controller
{
    public function about(SiteSettings $settings)
    {
        return view('about', compact('settings'));
    }
}
