<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::active()
            ->orderBy('sort_order')
            ->paginate(6);

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
