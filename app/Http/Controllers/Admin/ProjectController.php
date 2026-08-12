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

        $projects = $query->paginate(10)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
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

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $featuredUrl = $project->getFeaturedImageUrl();
        $galleryImages = $project->getGalleryImages();
        return view('admin.projects.edit', compact('project', 'featuredUrl', 'galleryImages'));
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

        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')
                         ->with('success', 'Project deleted successfully.');
    }
}
