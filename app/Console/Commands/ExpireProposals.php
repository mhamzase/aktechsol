<?php

namespace App\Console\Commands;

use App\Models\Proposal;
use App\Support\NotifyAdmins;
use Illuminate\Console\Command;

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
        }

        if ($upcoming->count()) {
            $this->info("Sent {$upcoming->count()} expiry reminder(s).");
        }

        // Flip invoices past their due date to overdue.
        $overdue = \App\Models\Invoice::needsOverdue()->update(['status' => 'overdue']);
        $this->info("Marked {$overdue} invoice(s) as overdue.");

        return self::SUCCESS;
    }
}
