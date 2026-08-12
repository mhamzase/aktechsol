<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::active()
            ->latest('sort_order')
            ->take(6)
            ->get();

        $projects = Project::active()
            ->latest('sort_order')
            ->take(6)
            ->get();

        $testimonials = Testimonial::active()
            ->latest('sort_order')
            ->take(20)
            ->get();

        return view('home', compact('services', 'projects', 'testimonials'));
    }
}
