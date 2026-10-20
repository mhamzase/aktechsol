<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

/**
 * NewsletterMail — a bulk email composed in Admin → Newsletter
 * Subscribers ("Write Email"). One queued copy per subscriber, so the
 * queue worker sends them in the background and a single bad address
 * never blocks the rest.
 */
class NewsletterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $emailSubject,
        public string $bodyHtml,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->emailSubject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter',
            with: [
                'bodyHtml'  => $this->bodyHtml,
                'subject'   => $this->emailSubject,
                'preheader' => Str::limit(trim(strip_tags($this->bodyHtml)), 90),
            ],
        );
    }
}
