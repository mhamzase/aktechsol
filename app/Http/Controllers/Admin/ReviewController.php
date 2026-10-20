<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReviewApprovedMail;
use App\Mail\ReviewRequestMail;
use App\Models\ClientProject;
use App\Models\Review;
use App\Models\Testimonial;
use App\Support\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * ReviewController — admin moderation of client reviews.
 *
 * Listing uses the shared x-admin.data-table pattern (AJAX JSON
 * {rows, pagination, stats}). Approve/reject/send-request return JSON
 * with fresh stats so the page updates without a refresh.
 */
class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['project'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('reviewer_name', 'like', "%{$s}%")
                ->orWhere('review', 'like', "%{$s}%")
                ->orWhereHas('project', fn($p) => $p->where('title', 'like', "%{$s}%")
                    ->orWhere('client_name', 'like', "%{$s}%")));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = (int) $request->get('per_page', 15);
        $reviews = $query->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.reviews._rows', ['reviews' => $reviews])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $reviews])->render(),
                'stats'      => "Showing {$reviews->firstItem()}–{$reviews->lastItem()} of {$reviews->total()}",
            ]);
        }

        return view('admin.reviews.index', ['stats' => $this->stats()]);
    }

    /** Show the review in a drawer. */
    public function show(Review $review)
    {
        $review->load(['project']);

        if (request()->ajax() || request()->expectsJson()) {
            return view('admin.reviews.show', compact('review'));
        }

        // Direct landing (email / notification link): listing auto-opens the drawer.
        return redirect()->route('admin.reviews.index', ['view' => $review->id]);
    }

    /** Approve (publish) a review — auto-creates a homepage testimonial for client reviews. */
    public function approve(Review $review)
    {
        $wasApproved = $review->status === 'approved';

        $review->update(['status' => 'approved', 'is_published' => true]);
        LogActivity::record('review_approved', $review, "Approved review by {$review->reviewer_name}");

        $message = $this->syncTestimonial($review)
            ? 'Review approved, published & added as a testimonial.'
            : 'Review approved and published.';

        // Tell the client their review is live — once, on the transition.
        if (! $wasApproved) {
            $this->mailClientReviewLive($review);
        }

        return response()->json(['message' => $message, 'stats' => $this->stats()]);
    }

    /** Show the edit form in a drawer. */
    public function edit(Review $review)
    {
        return view('admin.reviews.form', compact('review'));
    }

    /** Update a review from the drawer form. */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'rating'        => 'required|integer|min:1|max:5',
            'review'        => 'nullable|string|min:20',
            'status'        => 'required|in:' . implode(',', Review::STATUSES),
        ]);

        $wasApproved = $review->status === 'approved';

        $review->update($validated + ['is_published' => $validated['status'] === 'approved']);
        LogActivity::record('review_updated', $review, "Updated review by {$review->reviewer_name}");

        $message = 'Review updated.';
        if ($validated['status'] === 'approved' && $this->syncTestimonial($review)) {
            $message = 'Review updated & added as a testimonial.';
        }

        // Tell the client their review is live — once, on the transition.
        if ($validated['status'] === 'approved' && ! $wasApproved) {
            $this->mailClientReviewLive($review);
        }

        return response()->json(['message' => $message, 'stats' => $this->stats()]);
    }

    /**
     * Email the client that their review is now live (queued). Client
     * reviews only; a mail failure never breaks moderation.
     */
    protected function mailClientReviewLive(Review $review): void
    {
        if ($review->reviewer_type !== 'client') {
            return;
        }

        $email = $review->project?->client_email;
        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->queue(new ReviewApprovedMail($review));
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Auto-convert a client review into a homepage testimonial (once per review).
     * Returns true when a new testimonial was created.
     */
    protected function syncTestimonial(Review $review): bool
    {
        if ($review->reviewer_type !== 'client' || empty($review->review)) {
            return false;
        }

        return Testimonial::firstOrCreate(
            ['review_id' => $review->id],
            [
                'client_name' => $review->reviewer_name,
                'content'     => $review->review,
                'rating'      => $review->rating,
                'status'      => true, // active => shows on homepage immediately
            ]
        )->wasRecentlyCreated;
    }

    /** Reject a review. */
    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected', 'is_published' => false]);
        LogActivity::record('review_rejected', $review, "Rejected review by {$review->reviewer_name}");

        return response()->json(['message' => 'Review rejected.', 'stats' => $this->stats()]);
    }

    public function destroy(Review $review)
    {
        $name = $review->reviewer_name;
        $review->delete();
        LogActivity::record('review_deleted', null, "Deleted review by {$name}");

        return response()->json(['message' => 'Review deleted.', 'stats' => $this->stats()]);
    }

    /** Show the send-request form in a drawer (AJAX search supported). */
    public function requestForm(Request $request)
    {
        $projects = ClientProject::where('status', 'delivered')
            ->whereNotNull('client_email')
            // Hide projects whose review is already given & approved.
            ->whereNotExists(function ($q) {
                $q->selectRaw('1')->from('reviews')
                    ->whereColumn('reviews.client_project_id', 'client_projects.id')
                    ->where('reviews.status', 'approved');
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $s = $request->search;
                $q->where(fn($qq) => $qq->where('title', 'like', "%{$s}%")
                    ->orWhere('client_name', 'like', "%{$s}%")
                    ->orWhere('client_email', 'like', "%{$s}%"));
            })
            ->orderBy('title')
            ->get(['id', 'title', 'client_name', 'client_email']);

        // List-only HTML for the picker's AJAX search.
        if ($request->query('partial') === 'list') {
            return view('admin.reviews._project-options', compact('projects'));
        }

        return view('admin.reviews.request-form', compact('projects'));
    }

    /** Email a review request to the client of a project. */
    public function sendRequest(Request $request)
    {
        $validated = $request->validate([
            'client_project_id' => 'required|exists:client_projects,id',
        ]);

        $project = ClientProject::findOrFail($validated['client_project_id']);

        if (! $project->client_email) {
            return response()->json(['message' => 'No client email on this project.'], 422);
        }

        if ($project->status !== 'delivered') {
            return response()->json(['message' => 'Reviews can only be requested for delivered projects.'], 422);
        }

        // Create (or reuse) a pending review placeholder to hold the token.
        $review = Review::firstOrCreate(
            ['client_project_id' => $project->id, 'reviewer_type' => 'client', 'status' => 'pending'],
            ['reviewer_name' => $project->client_name, 'rating' => 5, 'token' => Str::random(48)]
        );
        if (! $review->token) {
            $review->update(['token' => Str::random(48)]);
        }

        try {
            Mail::to($project->client_email)->queue(new ReviewRequestMail($project, $review->token));
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Failed to queue email.'], 500);
        }

        LogActivity::record('review_requested', $review, "Sent review request for {$project->title} to {$project->client_email}");

        return response()->json(['message' => 'Review request emailed to client.', 'stats' => $this->stats()]);
    }

    protected function stats(): array
    {
        return [
            'total'    => Review::count(),
            'pending'  => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'rejected' => Review::where('status', 'rejected')->count(),
            'avg'      => round((float) Review::approved()->avg('rating'), 1),
        ];
    }
}
