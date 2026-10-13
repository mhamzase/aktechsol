<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * QuoteRequest — a visitor's "Get a Quote" enquiry.
 * Admin reviews it and builds a Proposal from it.
 */
class QuoteRequest extends Model
{
    use SoftDeletes;

    /** Available statuses. */
    public const STATUSES = ['new', 'reviewed', 'quoted', 'rejected'];

    protected $fillable = [
        'name', 'email', 'phone', 'company', 'service_id',
        'subject', 'requirements', 'budget_range', 'status',
    ];

    /** The service this quote is about (optional). */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /** Proposals built from this quote. */
    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }
}
