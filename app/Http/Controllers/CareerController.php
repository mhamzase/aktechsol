<?php

namespace App\Http\Controllers;

use App\Mail\JobApplicationMail;
use App\Models\Job;
use App\Models\JobApplication;
use App\Support\AdminMail;
use App\Support\NotifyAdmins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * CareerController — public careers pages.
 *
 * Open listings (Admin → Jobs) show on /careers; each listing page has
 * an apply form (portfolio URL required, PDF CV optional) that lands in
 * Admin → Job Applications, with a bell notification + admin email.
 */
class CareerController extends Controller
{
    /** Careers listing — open jobs and internships.
     *  20 per page; the page loads more via AJAX (Load More button)
     *  and the search box filters by title / description. */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $query = Job::visible()->withCount('applications');
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $jobs = $query->paginate(20);

        if ($request->ajax() || $request->query('ajax')) {
            return response()->json([
                'html'      => view('careers._cards', ['jobs' => $jobs, 'search' => $search])->render(),
                'total'     => $jobs->total(),
                'has_more'  => $jobs->hasMorePages(),
                'next_page' => $jobs->currentPage() + 1,
            ]);
        }

        return view('careers.index', compact('jobs', 'search'));
    }

    /** Single listing with the apply form. Closed listings 404. */
    public function show(string $slug)
    {
        $job = Job::visible()->where('slug', $slug)->firstOrFail();

        return view('careers.show', compact('job'));
    }

    /** Store an application for an open listing. Always answers JSON —
     *  this endpoint is used by the AJAX apply form only, so validation
     *  errors are returned manually instead of relying on the request
     *  headers (a redirect here would silently lose the application). */
    public function apply(Request $request, string $slug)
    {
        $job = Job::visible()->where('slug', $slug)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:50',
            'portfolio_url' => 'required|url|max:500',
            'cover_note'    => 'nullable|string|max:5000',
            'resume'        => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Please fix the highlighted fields.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $application = JobApplication::create([
            'job_id'        => $job->id,
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'] ?? null,
            'portfolio_url' => $validated['portfolio_url'],
            'cover_note'    => $validated['cover_note'] ?? null,
        ]);

        // A failed CV attach must never lose the application itself.
        if ($request->hasFile('resume')) {
            try {
                $application->addMediaFromRequest('resume')->toMediaCollection('resume');
            } catch (\Throwable $e) {
                report($e);
            }
        }

        NotifyAdmins::send(
            'job',
            'New job application',
            "{$application->name} applied for {$job->title}",
            route('admin.job-applications.show', $application),
        );
        AdminMail::queue(new JobApplicationMail($application));

        return response()->json(['message' => 'Application submitted successfully! Our team will review your work and get back to you.']);
    }
}
