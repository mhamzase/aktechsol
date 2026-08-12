<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::active()->orderBy('sort_order')->paginate(12);
        return view('services.index', compact('services'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->active()->firstOrFail();
        return view('services.show', compact('service'));
    }
}
