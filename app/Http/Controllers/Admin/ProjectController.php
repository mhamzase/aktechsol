<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query()->latest('sort_order');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('full_description', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status === 'active');
        }

        $projects = $query->paginate($request->get('per_page', 10));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.projects._rows', ['projects' => $projects])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $projects])->render(),
                'stats'      => "Showing {$projects->firstItem()}–{$projects->lastItem()} of {$projects->total()}",
            ]);
        }

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects._form', [
            'project' => new Project(),
            'featuredUrl' => null,
            'galleryImages' => [],
            'action' => route('admin.projects.store'),
            'method' => 'POST',
            'tableId' => 'projects-table',
            'formTitle' => 'Add Project',
        ]);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());

        if ($request->hasFile('featured_image')) {
            $project->addMediaFromRequest('featured_image')
                    ->toMediaCollection('featured_image');
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $project->addMedia($image)
                        ->toMediaCollection('gallery');
            }
        }

        if (request()->ajax()) {
            return response()->json(['message' => 'Project created successfully.']);
        }

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        return view('admin.projects._form', [
            'project' => $project,
            'featuredUrl' => $project->getFeaturedImageUrl(),
            'galleryImages' => $project->getGalleryImages(),
            'action' => route('admin.projects.update', $project),
            'method' => 'PUT',
            'tableId' => 'projects-table',
            'formTitle' => 'Edit Project',
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->fill($request->validated())->save();

        if ($request->hasFile('featured_image')) {
            $project->clearMediaCollection('featured_image');
            $project->addMediaFromRequest('featured_image')
                    ->toMediaCollection('featured_image');
        }

        if ($request->hasFile('gallery_images')) {
            // clear existing gallery images if new ones are uploaded
            $project->clearMediaCollection('gallery');
            foreach ($request->file('gallery_images') as $image) {
                $project->addMedia($image)
                        ->toMediaCollection('gallery');
            }
        }

        if (request()->ajax()) {
            return response()->json(['message' => 'Project updated successfully.']);
        }

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project updated successfully.');
    }

    /** Drawer: view project details. */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    public function destroy(Project $project)
    {
        $project->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Project deleted successfully.']);
        }

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project deleted successfully.');
    }

    /** Toggle active/inactive status via AJAX. */
    public function toggleStatus(Project $project)
    {
        $project->update(['status' => ! $project->status]);

        return response()->json([
            'message' => $project->status ? 'Project activated.' : 'Project deactivated.',
            'status' => (bool) $project->status,
        ]);
    }
}
