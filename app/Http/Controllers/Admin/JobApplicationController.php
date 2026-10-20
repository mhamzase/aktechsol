<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use App\Support\LogActivity;
use Illuminate\Http\Request;

/**
 * JobApplicationController (admin) — applications from the careers page.
 * Listing + detail drawer + status management, mirroring Quote Requests.
 */
class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with('job')->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('job', fn ($j) => $j->where('title', 'like', "%{$search}%"));
            });
        }

        if ($jobId = $request->query('job_id')) {
            $query->where('job_id', $jobId);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $applications = $query->paginate($request->get('per_page', 10));
        $stats = $this->stats();
        $jobs = Job::orderBy('title')->get(['id', 'title']);

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.job-applications._rows', compact('applications'))->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $applications])->render(),
                'stats'      => "Showing {$applications->firstItem()}–{$applications->lastItem()} of {$applications->total()}",
            ]);
        }

        return view('admin.job-applications.index', compact('applications', 'stats', 'jobs'));
    }

    public function show(JobApplication $jobApplication)
    {
        $jobApplication->load('job');

        if (request()->ajax() || request()->expectsJson()) {
            return view('admin.job-applications.show', ['application' => $jobApplication]);
        }

        return redirect()->route('admin.job-applications.index', ['view' => $jobApplication->id]);
    }

    public function updateStatus(Request $request, JobApplication $jobApplication)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', JobApplication::STATUSES),
        ]);

        $jobApplication->update(['status' => $validated['status']]);
        LogActivity::record('application_status_updated', $jobApplication, "Application #{$jobApplication->id} status → {$validated['status']}");

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Application status updated.', 'status' => $jobApplication->status, 'stats' => $this->stats()]);
        }

        return back()->with('success', 'Application status updated.');
    }

    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();
        LogActivity::record('application_deleted', null, "Deleted job application from {$jobApplication->name}");

        if (request()->ajax()) {
            return response()->json(['message' => 'Application deleted.']);
        }

        return redirect()->route('admin.job-applications.index')->with('success', 'Application deleted.');
    }

    /** Counters for the listing stat cards. */
    protected function stats(): array
    {
        return [
            'new'         => JobApplication::where('status', 'new')->count(),
            'reviewed'    => JobApplication::where('status', 'reviewed')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'total'       => JobApplication::count(),
        ];
    }
}
