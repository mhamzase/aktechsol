<?php

namespace App\Mail;

use App\Models\ClientProject;
use App\Models\ProjectUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProjectUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public ClientProject $project,
        public ProjectUpdate $update,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Project Update: ' . $this->update->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.project-update',
            with: [
                'project' => $this->project,
                'update' => $this->update,
                'subject' => 'Project Update',
                'preheader' => 'New update on your project',
            ],
        );
    }
}
