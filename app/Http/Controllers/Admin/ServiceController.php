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
        return view('admin.services._form', [
            'service' => new Service(),
            'categories' => ServiceCategory::orderBy('name')->get(),
            'thumbnailUrl' => null,
            'action' => route('admin.services.store'),
            'method' => 'POST',
            'tableId' => 'services-table',
            'formTitle' => 'Add Service',
        ]);
    }

    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->validated());

        if ($request->hasFile('image')) {
            $service->addMediaFromRequest('image')
                ->toMediaCollection('service_image');
        }

        if (request()->ajax()) {
            return response()->json(['message' => 'Service created successfully.']);
        }

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /** Drawer: view service details. */
    public function show(Service $service)
    {
        $service->load('category');

        return view('admin.services.show', compact('service'));
    }

      public function edit(Service $service)
    {
        return view('admin.services._form', [
            'service' => $service,
            'categories' => ServiceCategory::orderBy('name')->get(),
            'thumbnailUrl' => $service->getThumbnailUrl(),
            'action' => route('admin.services.update', $service),
            'method' => 'PUT',
            'tableId' => 'services-table',
            'formTitle' => 'Edit Service',
        ]);
    }

    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->fill($request->validated())->save();

        if ($request->hasFile('image')) {
            $service->clearMediaCollection('service_image');
            $service->addMediaFromRequest('image')
                ->toMediaCollection('service_image');
        }

        if (request()->ajax()) {
            return response()->json(['message' => 'Service updated successfully.']);
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

    /** Toggle active/inactive status via AJAX. */
    public function toggleStatus(Service $service)
    {
        $service->update(['status' => ! $service->status]);

        return response()->json([
            'message' => $service->status ? 'Service activated.' : 'Service deactivated.',
            'status' => (bool) $service->status,
        ]);
    }
}
