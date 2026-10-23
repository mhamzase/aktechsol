<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One entry in a proposal's feedback history: a response the client
 * gave (accepted / changes_requested / rejected, with their note) or
 * a revised version the admin sent back. Entries are never edited or
 * overwritten, so every round of the conversation is preserved.
 */
class ProposalResponse extends Model
{
    protected $fillable = [
        'proposal_id',
        'type',
        'action',
        'note',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
