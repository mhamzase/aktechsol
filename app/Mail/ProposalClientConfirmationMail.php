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
 * ProposalClientConfirmationMail — queued confirmation to the CLIENT after
 * they respond to a proposal (accepted / changes requested / declined),
 * mirroring what they just did on the proposal page.
 */
class ProposalClientConfirmationMail extends Mailable implements ShouldQueue
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
            'accepted' => 'Thank you — your proposal is accepted: ',
            'changes_requested' => 'We received your requested changes: ',
            'rejected' => 'Thank you for your time: ',
        ];

        return new Envelope(
            subject: ($subjects[$this->action] ?? 'Proposal update: ') . $this->proposal->title,
        );
    }

    public function content(): Content
    {
        // Client-facing copy per action (heading, intro, what happens next).
        $copy = [
            'accepted' => [
                'heading' => 'Proposal Accepted — Thank You!',
                'intro' => 'You have accepted the proposal below. This email confirms we have received your acceptance.',
                'next' => 'We have already created your project in our system. Our team will be in touch shortly with the next steps.',
                'preheader' => 'Your acceptance is confirmed — your project is being set up',
            ],
            'changes_requested' => [
                'heading' => 'Changes Requested — Received',
                'intro' => 'You asked for some changes to the proposal below. This email confirms we have received your feedback.',
                'next' => 'Our team will review your notes and share an updated proposal with you soon.',
                'preheader' => 'We received your feedback and will update the proposal',
            ],
            'rejected' => [
                'heading' => 'Response Recorded — Thank You',
                'intro' => 'You have declined the proposal below. This email confirms your response has been recorded.',
                'next' => 'Thank you for considering AK Tech SOL. If your plans change, we would be happy to put together a fresh proposal anytime.',
                'preheader' => 'Your response to the proposal has been recorded',
            ],
        ][$this->action] ?? [];

        return new Content(
            view: 'emails.proposal-client-confirmation',
            with: [
                'proposal' => $this->proposal,
                'copy' => $copy,
                'subject' => 'Proposal ' . str_replace('_', ' ', $this->action),
                'preheader' => $copy['preheader'] ?? 'An update on your proposal',
            ],
        );
    }
}
