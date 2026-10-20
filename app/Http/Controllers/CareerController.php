<?php

namespace App\Http\Controllers;

use App\Mail\JobApplicationMail;
use App\Models\Job;
use App\Models\JobApplication;
use App\Support\AdminMail;
use App\Support\NotifyAdmins;
use Illuminate\Http\Request;

/**
 * CareerController — public careers pages.
 *
 * Open listings (Admin → Jobs) show on /careers; each listing page has
 * an apply form (portfolio URL required, PDF CV optional) that lands in
 * Admin → Job Applications, with a bell notification + admin email.
 */
class CareerController extends Controller
{
    /** Careers listing — open jobs and internships. */
    public function index()
    {
        $jobs = Job::visible()->withCount('applications')->get();

        return view('careers.index', compact('jobs'));
    }

    /** Single listing with the apply form. Closed listings 404. */
    public function show(string $slug)
    {
        $job = Job::visible()->where('slug', $slug)->firstOrFail();

        return view('careers.show', compact('job'));
    }

    /** Store an application for an open listing. */
    public function apply(Request $request, string $slug)
    {
        $job = Job::visible()->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:50',
            'portfolio_url' => 'required|url|max:500',
            'cover_note'    => 'nullable|string|max:5000',
            'resume'        => 'nullable|file|mimes:pdf|max:5120',
        ]);

        $application = JobApplication::create([
            'job_id'        => $job->id,
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'phone'         => $validated['phone'] ?? null,
            'portfolio_url' => $validated['portfolio_url'],
            'cover_note'    => $validated['cover_note'] ?? null,
        ]);

        if ($request->hasFile('resume')) {
            $application->addMediaFromRequest('resume')->toMediaCollection('resume');
        }

        NotifyAdmins::send(
            'job',
            'New job application',
            "{$application->name} applied for {$job->title}",
            route('admin.job-applications.show', $application),
        );
        AdminMail::queue(new JobApplicationMail($application));

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['message' => 'Application submitted successfully! Our team will review your work and get back to you.']);
        }

        return redirect()->route('careers.show', $job->slug)->with('success', 'Application submitted successfully!');
    }
}
