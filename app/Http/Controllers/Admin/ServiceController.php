<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with('category')->latest('sort_order');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('full_description', 'like', "%{$search}%")
                    ->orWhere('icon', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($catQuery) use ($search) {
                        $catQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status === 'active');
        }

        $services = $query->paginate($request->get('per_page', 10));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.services._rows', compact('services'))->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $services])->render(),
                'stats'      => "Showing {$services->firstItem()}–{$services->lastItem()} of {$services->total()}",
            ]);
        }

        return view('admin.services.index');
    }

    public function create()
    {
        $categories = ServiceCategory::orderBy('name')->get();
        return view('admin.services.create', compact('categories'));
    }

    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->validated());

        if ($request->hasFile('image')) {
            $service->addMediaFromRequest('image')
                ->toMediaCollection('service_image');
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

  public function edit(Service $service)
{
    $categories = ServiceCategory::orderBy('name')->get();
    $thumbnailUrl = $service->getThumbnailUrl();
    return view('admin.services.edit', compact('service', 'categories', 'thumbnailUrl'));
}

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->fill($request->validated())->save();

        if ($request->hasFile('image')) {
            $service->clearMediaCollection('service_image');
            $service->addMediaFromRequest('image')
                ->toMediaCollection('service_image');
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Service deleted successfully.']);
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
