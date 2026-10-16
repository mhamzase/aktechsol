<?php

namespace App\Mail;

use App\Models\ClientProject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * ReviewRequestMail — queued email asking the client to review their project.
 */
class ReviewRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public ClientProject $project,
        public string $reviewToken,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We'd love your feedback: {$this->project->title}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.review-request',
            with: [
                'project' => $this->project,
                'reviewUrl' => route('reviews.form', $this->reviewToken),
                'subject' => "We'd love your feedback",
                'preheader' => 'How was your experience working with us?',
            ],
        );
    }
}
