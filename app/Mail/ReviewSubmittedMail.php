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
 * ReviewSubmittedMail — queued email to the notification email when a client
 * submits a review (needs moderation).
 */
class ReviewSubmittedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Review $review,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New review submitted: ' . $this->review->reviewer_name,
        );
    }

    public function content(): Content
    {
        $this->review->loadMissing('project');
        return new Content(
            view: 'emails.review-submitted',
            with: [
                'review' => $this->review,
                'subject' => 'New Review Submitted',
                'preheader' => 'A client submitted a review for moderation',
            ],
        );
    }
}
