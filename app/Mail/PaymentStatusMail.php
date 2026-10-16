<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * PaymentStatusMail — queued email to the client when their payment proof
 * is verified or rejected.
 */
class PaymentStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice,
        public InvoicePayment $payment,
        public bool $verified,
    ) {}

    public function envelope(): Envelope
    {
        $status = $this->verified ? 'Verified' : 'Rejected';
        return new Envelope(
            subject: "Payment {$status}: {$this->invoice->invoice_no}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-status',
            with: [
                'invoice' => $this->invoice,
                'payment' => $this->payment,
                'verified' => $this->verified,
                'subject' => 'Payment ' . ($this->verified ? 'Verified' : 'Rejected'),
                'preheader' => 'Update on your payment',
            ],
        );
    }
}
