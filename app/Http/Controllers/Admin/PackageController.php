<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicePackage;
use App\Support\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * PackageController (admin) — pricing packages (Starter / Growth / Pro).
 * Drawer CRUD mirroring Services; packages show on the website through
 * ServicePackage::visible() (homepage pricing section + /pricing page).
 */
class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = ServicePackage::orderBy('sort_order')->orderBy('name');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('is_active', $status === 'active');
        }

        $packages = $query->paginate($request->get('per_page', 10));
        $stats = $this->stats();

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.packages._rows', compact('packages'))->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $packages])->render(),
                'stats'      => "Showing {$packages->firstItem()}–{$packages->lastItem()} of {$packages->total()}",
            ]);
        }

        return view('admin.packages.index', compact('packages', 'stats'));
    }

    public function create()
    {
        return view('admin.packages._form', [
            'package'   => new ServicePackage(['is_active' => true]),
            'action'    => route('admin.packages.store'),
            'method'    => 'POST',
            'tableId'   => 'packages-table',
            'formTitle' => 'Add Package',
        ]);
    }

    public function store(Request $request)
    {
        $package = ServicePackage::create($this->validated($request) + [
            'slug' => $this->uniqueSlug($request->input('name')),
        ]);
        LogActivity::record('package_created', $package, "Created package {$package->name}");

        if ($request->ajax()) {
            return response()->json(['message' => 'Package created successfully.', 'stats' => $this->stats()]);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(ServicePackage $package)
    {
        return view('admin.packages._form', [
            'package'   => $package,
            'action'    => route('admin.packages.update', $package),
            'method'    => 'PUT',
            'tableId'   => 'packages-table',
            'formTitle' => 'Edit Package',
        ]);
    }

    public function update(Request $request, ServicePackage $package)
    {
        // The slug never changes on edit, so existing links keep working.
        $package->update($this->validated($request));
        LogActivity::record('package_updated', $package, "Updated package {$package->name}");

        if ($request->ajax()) {
            return response()->json(['message' => 'Package updated successfully.', 'stats' => $this->stats()]);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(ServicePackage $package)
    {
        $package->delete();
        LogActivity::record('package_deleted', null, "Deleted package {$package->name}");

        if (request()->ajax()) {
            return response()->json(['message' => 'Package deleted successfully.']);
        }

        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully.');
    }

    /** Validate the form and shape the data (features: one per line → array). */
    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'price'         => 'required|numeric|min:0',
            'price_label'   => 'nullable|string|max:100',
            'turnaround'    => 'nullable|string|max:100',
            'features_text' => 'nullable|string|max:5000',
            'is_popular'    => 'nullable|boolean',
            'is_active'     => 'nullable|boolean',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        $features = collect(preg_split('/\r\n|\r|\n/', (string) ($data['features_text'] ?? '')))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();

        return [
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'],
            'price_label' => $data['price_label'] ?? null,
            'turnaround'  => $data['turnaround'] ?? null,
            'features'    => $features,
            'is_popular'  => (bool) ($data['is_popular'] ?? false),
            'is_active'   => (bool) ($data['is_active'] ?? false),
            'sort_order'  => $data['sort_order'] ?? 0,
        ];
    }

    /** Slug from the name, suffixed (-2, -3…) when already taken. */
    protected function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'package';
        $slug = $base;
        $i = 2;
        while (ServicePackage::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /** Counters for the listing stat cards. */
    protected function stats(): array
    {
        return [
            'total'   => ServicePackage::count(),
            'active'  => ServicePackage::where('is_active', true)->count(),
            'popular' => ServicePackage::where('is_popular', true)->count(),
        ];
    }
}
