<?php

namespace App\Mail;

use App\Models\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProposalResponseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /** @param 'accepted'|'changes_requested'|'rejected' $action */
    public function __construct(
        public Proposal $proposal,
        public string $action,
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'accepted' => '✅ Proposal Accepted: ',
            'changes_requested' => '📝 Changes Requested: ',
            'rejected' => '❌ Proposal Declined: ',
        ];

        return new Envelope(
            subject: ($subjects[$this->action] ?? '') . $this->proposal->title,
        );
    }

    public function content(): Content
    {
        $views = [
            'accepted' => 'emails.proposal-accepted',
            'changes_requested' => 'emails.proposal-changes',
            'rejected' => 'emails.proposal-declined',
        ];

        return new Content(
            view: $views[$this->action] ?? 'emails.proposal-accepted',
            with: [
                'proposal' => $this->proposal,
                'subject' => 'Proposal Update',
                'preheader' => 'Client responded to proposal',
            ],
        );
    }
}
