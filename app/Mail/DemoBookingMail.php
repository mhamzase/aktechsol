<?php

namespace App\Mail;

use App\Models\DemoBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * DemoBookingMail — sent to the admin (notification email, or the
 * Administrator-role fallback) whenever a visitor books a demo.
 */
class DemoBookingMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public DemoBooking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New Demo Booking — ' . $this->booking->name);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.demo-booking', with: ['booking' => $this->booking]);
    }
}
