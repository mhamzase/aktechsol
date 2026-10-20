<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * ProposalExpiringMail — queued reminder to the client 3 days before a
 * proposal's valid_until date (sent once, from the proposals:expire command).
 */
class ProposalExpiringMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Proposal $proposal,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your proposal expires soon: ' . $this->proposal->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proposal-expiring',
            with: [
                'proposal' => $this->proposal,
                'subject' => 'Proposal Expiring Soon',
                'preheader' => 'Your proposal is valid for 3 more days',
            ],
        );
    }
}
