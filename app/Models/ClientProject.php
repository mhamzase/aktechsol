<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * ClientProject — a project created from an accepted proposal.
 * The client follows it live through a secure token tracking link.
 */
class ClientProject extends Model
{
    use SoftDeletes;

    /** Pipeline stages shown on the tracking link. */
    public const STATUSES = [
        'received'    => 'Received',
        'in_progress' => 'In Progress',
        'review'      => 'Review',
        'revision'    => 'Revision',
        'delivered'   => 'Delivered',
    ];

    protected $fillable = [
        'proposal_id', 'title', 'client_name', 'client_email',
        'status', 'progress', 'token',
        'started_at', 'deadline',
    ];

    protected $casts = [
        'started_at' => 'date',
        'deadline'   => 'date',
    ];

    /** Auto-generate the secure tracking token on creation. */
    protected static function booted(): void
    {
        static::creating(function (ClientProject $project) {
            $project->token ??= Str::random(48);
        });
    }

    /** The proposal this project came from (optional). */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    /** Team members assigned to this project (many-to-many). */
    public function team(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'client_project_team')->withTimestamps();
    }

    /** Timeline entries, newest first. */
    public function updates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class)->latest();
    }

    /** Client-visible timeline entries, newest first. */
    public function visibleUpdates(): HasMany
    {
        return $this->hasMany(ProjectUpdate::class)->where('is_client_visible', true)->latest();
    }

    /** Invoices raised against this project. */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /** Revenue shares recorded for this project's received payment. */
    public function revenueShares(): HasMany
    {
        return $this->hasMany(ProjectRevenueShare::class, 'client_project_id');
    }

    /** Total actually received for this project (paid invoice amounts). */
    public function receivedAmount(): float
    {
        return (float) $this->invoices()->sum('paid_amount');
    }

    /** True once any share is paid: the percentages are then locked. */
    public function revenueLocked(): bool
    {
        return $this->revenueShares()->where('status', 'paid')->exists();
    }

    /** Reviews collected after delivery. */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /** Public secure tracking link for the client. */
    public function trackingUrl(): string
    {
        // Self-heals legacy rows that have no token yet.
        if (! $this->token) {
            $this->forceFill(['token' => Str::random(48)])->saveQuietly();
        }

        return route('projects.track', $this->token);
    }

    /** Human label for the current status. */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
