<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * TeamMemberWelcomeMail — queued welcome email when an admin creates a
 * new team member account. The password is NOT included (the admin set
 * it); the email points the member to the login + forgot-password pages.
 */
class TeamMemberWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to AK Tech SOL — your account is ready',
        );
    }

    public function content(): Content
    {
        $this->user->loadMissing('roles');

        return new Content(
            view: 'emails.team-welcome',
            with: [
                'user' => $this->user,
                'subject' => 'Welcome to AK Tech SOL',
                'preheader' => 'Your team account has been created',
            ],
        );
    }
}
