<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * ComingSoonController — placeholder for modules landing in later phases.
 * Every sidebar link resolves to this until its phase replaces the route
 * with the real module controller. Keeps the admin navigable at all times.
 */
class ComingSoonController extends Controller
{
    public function show(string $title = 'Module', string $phase = 'a future phase')
    {
        return view('admin.coming-soon', compact('title', 'phase'));
    }
}
