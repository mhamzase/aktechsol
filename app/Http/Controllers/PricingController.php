<?php

namespace App\Http\Controllers;

use App\Models\ServicePackage;

/**
 * PricingController — public pricing page.
 * Packages are managed in Admin → Packages; only active ones show.
 */
class PricingController extends Controller
{
    public function index()
    {
        $packages = ServicePackage::visible()->get();

        return view('pricing', compact('packages'));
    }
}
