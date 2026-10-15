<?php

namespace App\Console\Commands;

use App\Models\Proposal;
use App\Support\LogActivity;
use Illuminate\Console\Command;

class ExpireProposals extends Command
{
    protected $signature = 'proposals:expire';
    protected $description = 'Mark proposals past their valid_until date as expired.';

    public function handle(): int
    {
        $count = Proposal::whereNotIn('status', ['accepted', 'rejected', 'expired'])
            ->whereNotNull('valid_until')
            ->whereDate('valid_until', '<', today())
            ->update(['status' => 'expired']);

        $this->info("Marked {$count} proposal(s) as expired.");

        return self::SUCCESS;
    }
}
