<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * ReviewApprovedMail — queued email to the client when their review is
 * approved and published (shows on the homepage as a testimonial).
 * Sent once, on the transition into the approved status.
 */
class ReviewApprovedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Review $review,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your review is now live. Thank you!',
        );
    }

    public function content(): Content
    {
        $this->review->loadMissing('project');

        return new Content(
            view: 'emails.review-approved',
            with: [
                'review' => $this->review,
                'subject' => 'Your Review Is Live',
                'preheader' => 'Your review has been published on our website',
            ],
        );
    }
}
