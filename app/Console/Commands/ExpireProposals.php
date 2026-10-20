<?php

namespace App\Console\Commands;

use App\Mail\InvoiceOverdueMail;
use App\Mail\ProposalExpiringMail;
use App\Models\Proposal;
use App\Support\NotifyAdmins;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class ExpireProposals extends Command
{
    protected $signature = 'proposals:expire';
    protected $description = 'Mark proposals past their valid_until date as expired + remind about upcoming expiries.';

    public function handle(): int
    {
        $count = Proposal::whereNotIn('status', ['accepted', 'rejected', 'expired'])
            ->whereNotNull('valid_until')
            ->whereDate('valid_until', '<', today())
            ->update(['status' => 'expired']);

        $this->info("Marked {$count} proposal(s) as expired.");

        // Remind admins about proposals expiring in 3 days.
        $upcoming = Proposal::whereIn('status', ['sent', 'changes_requested'])
            ->whereDate('valid_until', today()->addDays(3))
            ->get();

        foreach ($upcoming as $proposal) {
            NotifyAdmins::send(
                'proposal',
                'Proposal expiring soon',
                "Proposal '{$proposal->title}' for {$proposal->client_name} expires on {$proposal->valid_until->format('M d, Y')}.",
                route('admin.proposals.show', $proposal),
            );

            // Remind the client too (queued) — exact-date match above means
            // this fires exactly once, 3 days before expiry.
            if ($proposal->client_email) {
                Mail::to($proposal->client_email)->queue(new ProposalExpiringMail($proposal));
            }
        }

        if ($upcoming->count()) {
            $this->info("Sent {$upcoming->count()} expiry reminder(s).");
        }

        // Flip invoices past their due date to overdue — one by one so each
        // client gets exactly one overdue reminder (queued) on the day the
        // status flips; the scope excludes already-overdue invoices.
        $overdueInvoices = \App\Models\Invoice::needsOverdue()->get();

        foreach ($overdueInvoices as $invoice) {
            $invoice->update(['status' => 'overdue']);

            $clientEmail = $invoice->clientEmail();
            if ($clientEmail) {
                Mail::to($clientEmail)->queue(new InvoiceOverdueMail($invoice));
            }
        }

        if ($overdueInvoices->count()) {
            NotifyAdmins::send(
                'invoice',
                'Invoices became overdue',
                "{$overdueInvoices->count()} invoice(s) just flipped to overdue.",
                route('admin.invoices.index'),
            );
            $this->info("Marked {$overdueInvoices->count()} invoice(s) as overdue.");
        }

        return self::SUCCESS;
    }
}
