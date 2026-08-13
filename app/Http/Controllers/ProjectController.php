<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::active();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('full_description', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderBy('sort_order')->paginate(6);

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'items' => view('projects._cards', compact('projects'))->render(),
                'next_page_url' => $projects->nextPageUrl(),
            ]);
        }

        return view('projects.index', compact('projects'));
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)->active()->firstOrFail();
        $gallery = $project->getGalleryImages();
        return view('projects.show', compact('project', 'gallery'));
    }
}
