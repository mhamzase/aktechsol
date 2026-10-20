<?php

namespace App\Http\Controllers;

use App\Mail\ReviewSubmittedMail;
use App\Models\Review;
use App\Support\NotifyAdmins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * ClientReviewController — secure client review form at /review/{token}.
 *
 * The token belongs to a Review placeholder (created when the project is
 * delivered or when an admin sends a request). The client fills it in;
 * it stays pending until an admin approves it.
 */
class ClientReviewController extends Controller
{
    /** Show the review form (or an already-submitted state). */
    public function showForm(string $token)
    {
        $review = Review::where('token', $token)->with(['project'])->firstOrFail();

        $project = $review->project;
        $alreadySubmitted = ! empty($review->review);
        $projectDelivered = $project && $project->status === 'delivered';

        return view('reviews.form', compact('review', 'project', 'alreadySubmitted', 'projectDelivered'));
    }

    /** Submit the review (AJAX). */
    public function submit(Request $request, string $token)
    {
        $review = Review::where('token', $token)->with(['project'])->firstOrFail();

        if (! empty($review->review)) {
            return response()->json(['message' => 'You have already submitted your review. Thank you!'], 422);
        }

        if (! $review->project || $review->project->status !== 'delivered') {
            return response()->json(['message' => 'This review link is not active yet.'], 422);
        }

        $validated = $request->validate([
            'rating'        => 'required|integer|min:1|max:5',
            'reviewer_name' => 'required|string|max:255',
            'review'        => 'required|string|min:20|max:2000',
        ]);

        $review->update([
            'rating'        => $validated['rating'],
            'reviewer_name' => $validated['reviewer_name'],
            'review'        => $validated['review'],
            'reviewer_type' => 'client',
            'status'        => 'pending',
            'is_published'  => false,
        ]);

        // Notify admins: system notification + queued email.
        try {
            NotifyAdmins::send(
                'review',
                'New review submitted',
                "{$review->reviewer_name} submitted a {$review->rating}-star review for {$review->project->title}.",
                route('admin.reviews.show', $review)
            );

            \App\Support\AdminMail::queue(new ReviewSubmittedMail($review));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['message' => 'Thank you! Your review has been submitted for moderation.']);
    }
}
