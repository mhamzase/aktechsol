<?php

namespace App\Mail;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * QuoteRequestMail — queued email to the notification email when a visitor
 * submits the public Get a Quote form.
 */
class QuoteRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public QuoteRequest $quote,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New quote request from ' . $this->quote->name,
        );
    }

    public function content(): Content
    {
        $this->quote->loadMissing('service');

        return new Content(
            view: 'emails.quote-request',
            with: [
                'quote' => $this->quote,
                'subject' => 'New Quote Request',
                'preheader' => 'A visitor requested a quote on the website',
            ],
        );
    }
}
