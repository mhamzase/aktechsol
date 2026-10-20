<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Support\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * JobController (admin) — job / internship listings.
 * Drawer CRUD mirroring Services; open listings show on /careers.
 */
class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::withCount('applications')->orderBy('sort_order')->orderBy('title');

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        if ($status = $request->query('status')) {
            $query->where('is_open', $status === 'open');
        }

        $jobs = $query->paginate($request->get('per_page', 10));
        $stats = $this->stats();

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.jobs._rows', compact('jobs'))->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $jobs])->render(),
                'stats'      => "Showing {$jobs->firstItem()}–{$jobs->lastItem()} of {$jobs->total()}",
            ]);
        }

        return view('admin.jobs.index', compact('jobs', 'stats'));
    }

    public function create()
    {
        return view('admin.jobs._form', [
            'job'       => new Job(['is_open' => true, 'type' => 'job', 'location_type' => 'remote']),
            'action'    => route('admin.jobs.store'),
            'method'    => 'POST',
            'tableId'   => 'jobs-table',
            'formTitle' => 'Add Job',
        ]);
    }

    /** Drawer: view the listing exactly as formatted (rich HTML). */
    public function show(Job $job)
    {
        $job->loadCount('applications');

        if (request()->ajax() || request()->expectsJson()) {
            return view('admin.jobs.show', compact('job'));
        }

        return redirect()->route('admin.jobs.index', ['view' => $job->id]);
    }

    public function store(Request $request)
    {
        $job = Job::create($this->validated($request) + [
            'slug' => $this->uniqueSlug($request->input('title')),
        ]);
        LogActivity::record('job_created', $job, "Created job listing {$job->title}");

        if ($request->ajax()) {
            return response()->json(['message' => 'Job created successfully.', 'stats' => $this->stats()]);
        }

        return redirect()->route('admin.jobs.index')->with('success', 'Job created successfully.');
    }

    public function edit(Job $job)
    {
        return view('admin.jobs._form', [
            'job'       => $job,
            'action'    => route('admin.jobs.update', $job),
            'method'    => 'PUT',
            'tableId'   => 'jobs-table',
            'formTitle' => 'Edit Job',
        ]);
    }

    public function update(Request $request, Job $job)
    {
        // The slug never changes on edit, so existing links keep working.
        $job->update($this->validated($request));
        LogActivity::record('job_updated', $job, "Updated job listing {$job->title}");

        if ($request->ajax()) {
            return response()->json(['message' => 'Job updated successfully.', 'stats' => $this->stats()]);
        }

        return redirect()->route('admin.jobs.index')->with('success', 'Job updated successfully.');
    }

    public function destroy(Job $job)
    {
        $job->delete();
        LogActivity::record('job_deleted', null, "Deleted job listing {$job->title}");

        if (request()->ajax()) {
            return response()->json(['message' => 'Job deleted successfully.']);
        }

        return redirect()->route('admin.jobs.index')->with('success', 'Job deleted successfully.');
    }

    /** Validate the listing form. */
    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'type'          => 'required|in:job,internship',
            'location_type' => 'required|in:remote,onsite,hybrid',
            'description'   => 'required|string|max:10000',
            'requirements'  => 'nullable|string|max:10000',
            'is_open'       => 'nullable|boolean',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        return [
            'title'         => $data['title'],
            'type'          => $data['type'],
            'location_type' => $data['location_type'],
            'description'   => $data['description'],
            'requirements'  => $data['requirements'] ?? null,
            'is_open'       => (bool) ($data['is_open'] ?? false),
            'sort_order'    => $data['sort_order'] ?? 0,
        ];
    }

    /** Slug from the title, suffixed (-2, -3…) when already taken. */
    protected function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'job';
        $slug = $base;
        $i = 2;
        while (Job::withTrashed()->where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    /** Counters for the listing stat cards. */
    protected function stats(): array
    {
        return [
            'total'        => Job::count(),
            'open'         => Job::where('is_open', true)->count(),
            'internships'  => Job::where('type', 'internship')->count(),
            'applications' => JobApplication::count(),
        ];
    }
}
