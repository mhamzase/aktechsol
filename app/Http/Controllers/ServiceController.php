<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $services = Service::active()
            ->orderBy('sort_order')
            ->paginate(6);

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'items' => view('services._cards', compact('services'))->render(),
                'next_page_url' => $services->nextPageUrl(),
            ]);
        }

        return view('services.index', compact('services'));
    }

    public function show($slug)
    {
        $service = Service::where('slug', $slug)->active()->firstOrFail();
        return view('services.show', compact('service'));
    }
}
