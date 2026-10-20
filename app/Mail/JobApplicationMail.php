<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * JobApplicationMail — sent to the admin (notification email, or the
 * Administrator-role fallback) whenever a candidate applies on /careers.
 */
class JobApplicationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public JobApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Job Application: ' . $this->application->name);
    }

    public function content(): Content
    {
        $this->application->loadMissing('job');

        return new Content(view: 'emails.job-application', with: ['application' => $this->application]);
    }
}
