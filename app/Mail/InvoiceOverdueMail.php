<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * InvoiceOverdueMail — queued reminder to the client the day an invoice
 * flips to overdue (sent once, from the proposals:expire command).
 */
class InvoiceOverdueMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invoice $invoice,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Overdue: Invoice ' . $this->invoice->invoice_no,
        );
    }

    public function content(): Content
    {
        $this->invoice->loadMissing('project');

        return new Content(
            view: 'emails.invoice-overdue',
            with: [
                'invoice' => $this->invoice,
                'subject' => 'Invoice Overdue',
                'preheader' => 'A payment on your invoice is now overdue',
            ],
        );
    }
}
