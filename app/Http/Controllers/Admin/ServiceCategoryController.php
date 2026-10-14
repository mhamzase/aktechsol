<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceCategoryRequest;
use App\Http\Requests\Admin\UpdateServiceCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceCategory::query()->latest('sort_order');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status === 'active');
        }

        $categories = $query->paginate($request->get('per_page', 10));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.service-categories._rows', ['categories' => $categories])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $categories])->render(),
                'stats'      => "Showing {$categories->firstItem()}–{$categories->lastItem()} of {$categories->total()}",
            ]);
        }

        return view('admin.service-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.service-categories._form', [
            'serviceCategory' => new ServiceCategory(),
            'action' => route('admin.service-categories.store'),
            'method' => 'POST',
            'tableId' => 'service-categories-table',
            'formTitle' => 'Add Category',
        ]);
    }

    public function store(StoreServiceCategoryRequest $request)
    {
        ServiceCategory::create($request->validated());

        if (request()->ajax()) {
            return response()->json(['message' => 'Service category created successfully.']);
        }

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category created successfully.');
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return view('admin.service-categories._form', [
            'serviceCategory' => $serviceCategory,
            'action' => route('admin.service-categories.update', $serviceCategory),
            'method' => 'PUT',
            'tableId' => 'service-categories-table',
            'formTitle' => 'Edit Category',
        ]);
    }

    public function update(UpdateServiceCategoryRequest $request, ServiceCategory $serviceCategory)
    {
        $serviceCategory->fill($request->validated())->save();

        if (request()->ajax()) {
            return response()->json(['message' => 'Service category updated successfully.']);
        }

        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category updated successfully.');
    }

    /** Drawer: view category details. */
    public function show(ServiceCategory $serviceCategory)
    {
        $serviceCategory->loadCount('services');

        return view('admin.service-categories.show', ['category' => $serviceCategory]);
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Service category deleted successfully.']);
        }


        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Service category deleted successfully.');
    }

    /** Toggle active/inactive status via AJAX. */
    public function toggleStatus(ServiceCategory $serviceCategory)
    {
        $serviceCategory->update(['status' => ! $serviceCategory->status]);

        return response()->json([
            'message' => $serviceCategory->status ? 'Category activated.' : 'Category deactivated.',
            'status' => (bool) $serviceCategory->status,
        ]);
    }
}
