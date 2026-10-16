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
 * PaymentProofMail — queued email to the notification email when a client
 * submits a payment proof for an invoice.
 */
class PaymentProofMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice,
        public InvoicePayment $payment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Proof Submitted: ' . $this->invoice->invoice_no,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-proof',
            with: [
                'invoice' => $this->invoice,
                'payment' => $this->payment,
                'subject' => 'Payment Proof Submitted',
                'preheader' => 'A client submitted a payment proof',
            ],
        );
    }
}
