<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::active()->with('category');

        // Search filter
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('full_description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($categorySlug = $request->query('category')) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        $services = $query->orderBy('sort_order')->paginate(6);

        $categories = ServiceCategory::where('status', true)->orderBy('sort_order')->get();

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'items' => view('services._cards', compact('services'))->render(),
                'next_page_url' => $services->nextPageUrl(),
            ]);
        }

        return view('services.index', compact('services', 'categories'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->active()->firstOrFail();
        return view('services.show', compact('service'));
    }
}
