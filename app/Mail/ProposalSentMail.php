<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalSentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public Proposal $proposal) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Proposal from ' . $this->siteName() . ': ' . $this->proposal->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proposal-sent',
            with: [
                'proposal' => $this->proposal,
                'subject' => 'Your Proposal from ' . $this->siteName(),
                'preheader' => 'Review your personalized proposal',
            ],
        );
    }

    /** Brand name from Settings → General (falls back to the default). */
    protected function siteName(): string
    {
        try {
            return app(\App\Settings\SiteSettings::class)->site_name ?: 'AK Tech SOL';
        } catch (\Throwable $e) {
            return 'AK Tech SOL';
        }
    }
}
