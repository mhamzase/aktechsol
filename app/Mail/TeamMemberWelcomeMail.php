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
 * new team member account. Includes the password the admin set (passed
 * in plain text at creation time only) so the member can sign in right
 * away; it is never stored or shown anywhere else.
 */
class TeamMemberWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public ?string $plainPassword = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to ' . $this->siteName() . ': your account is ready',
        );
    }

    public function content(): Content
    {
        $this->user->loadMissing('roles');

        return new Content(
            view: 'emails.team-welcome',
            with: [
                'user' => $this->user,
                'password' => $this->plainPassword,
                'subject' => 'Welcome to ' . $this->siteName(),
                'preheader' => 'Your team account has been created',
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
