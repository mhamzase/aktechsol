<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTestimonialRequest;
use App\Http\Requests\Admin\UpdateTestimonialRequest;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimonial::query()->latest('sort_order');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('client_position', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status === 'active');
        }

        $testimonials = $query->paginate($request->get('per_page', 10));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.testimonials._rows', ['testimonials' => $testimonials])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $testimonials])->render(),
                'stats'      => "Showing {$testimonials->firstItem()}–{$testimonials->lastItem()} of {$testimonials->total()}",
            ]);
        }

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials._form', [
            'testimonial' => new Testimonial(),
            'photoUrl' => null,
            'action' => route('admin.testimonials.store'),
            'method' => 'POST',
            'tableId' => 'testimonials-table',
            'formTitle' => 'Add Testimonial',
        ]);
    }

    public function store(StoreTestimonialRequest $request)
    {
        $testimonial = Testimonial::create($request->validated());

        if ($request->hasFile('photo')) {
            $testimonial->addMediaFromRequest('photo')
                        ->toMediaCollection('photo');
        }

        if (request()->ajax()) {
            return response()->json(['message' => 'Testimonial created successfully.']);
        }

        return redirect()->route('admin.testimonials.index')
                         ->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials._form', [
            'testimonial' => $testimonial,
            'photoUrl' => $testimonial->getPhotoUrl(),
            'action' => route('admin.testimonials.update', $testimonial),
            'method' => 'PUT',
            'tableId' => 'testimonials-table',
            'formTitle' => 'Edit Testimonial',
        ]);
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $testimonial->fill($request->validated())->save();

        if ($request->hasFile('photo')) {
            $testimonial->clearMediaCollection('photo');
            $testimonial->addMediaFromRequest('photo')
                        ->toMediaCollection('photo');
        }

        if (request()->ajax()) {
            return response()->json(['message' => 'Testimonial updated successfully.']);
        }

        return redirect()->route('admin.testimonials.index')
                         ->with('success', 'Testimonial updated successfully.');
    }

    /** Drawer: view testimonial details. */
    public function show(Testimonial $testimonial)
    {
        return view('admin.testimonials.show', compact('testimonial'));
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Testimonial deleted successfully.']);
        }

        return redirect()->route('admin.testimonials.index')
                         ->with('success', 'Testimonial deleted successfully.');
    }

    /** Toggle active/inactive status via AJAX. */
    public function toggleStatus(Testimonial $testimonial)
    {
        $testimonial->update(['status' => ! $testimonial->status]);

        return response()->json([
            'message' => $testimonial->status ? 'Testimonial activated.' : 'Testimonial deactivated.',
            'status' => (bool) $testimonial->status,
        ]);
    }
}
