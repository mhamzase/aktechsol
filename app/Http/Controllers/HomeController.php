<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::active()->with('category')->orderBy('sort_order')->take(6)->get();
        return view('home', compact('services'));
    }
}
