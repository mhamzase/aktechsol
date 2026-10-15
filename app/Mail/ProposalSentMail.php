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
            subject: 'Your Proposal from AK Tech SOL: ' . $this->proposal->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.proposal-sent',
            with: [
                'proposal' => $this->proposal,
                'subject' => 'Your Proposal from AK Tech SOL',
                'preheader' => 'Review your personalized proposal',
            ],
        );
    }
}
