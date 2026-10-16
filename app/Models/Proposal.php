<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Proposal — formal proposal sent to a client.
 * The client opens it through a secure token link (no login needed)
 * and can accept / reject / request changes.
 */
class Proposal extends Model
{
    use SoftDeletes;

    /** Available statuses. */
    public const STATUSES = ['draft', 'sent', 'accepted', 'rejected', 'changes_requested', 'expired'];

    protected $fillable = [
        'quote_request_id', 'title', 'client_name', 'client_email',
        'scope', 'timeline', 'amount', 'milestones', 'valid_until',
        'status', 'token', 'sent_at', 'viewed_at', 'responded_at', 'client_note',
    ];

    protected $casts = [
        'milestones'   => 'array',
        'valid_until'  => 'date',
        'sent_at'      => 'datetime',
        'viewed_at'    => 'datetime',
        'responded_at' => 'datetime',
        'amount'       => 'decimal:2',
    ];

    /** Auto-generate the secure token on creation. */
    protected static function booted(): void
    {
        static::creating(function (Proposal $proposal) {
            $proposal->token ??= Str::random(48);
        });
    }

    /** The quote this proposal was built from (optional). */
    public function quoteRequest(): BelongsTo
    {
        return $this->belongsTo(QuoteRequest::class);
    }

    /** Projects created from this proposal. */
    public function clientProjects(): HasMany
    {
        return $this->hasMany(ClientProject::class);
    }

    /** The (first) project created from this proposal. */
    public function project()
    {
        return $this->hasOne(ClientProject::class);
    }

    /** Public secure link for the client. */
    public function publicUrl(): string
    {
        return route('proposals.show', $this->token);
    }

    /** Has the proposal passed its expiry date? */
    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until->isPast()
            && ! in_array($this->status, ['accepted', 'rejected']);
    }
}
