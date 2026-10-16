<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'assigned_to', 'status', 'progress', 'token',
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

    /** Team member responsible (AK Tech Sol side). */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
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

    /** Reviews collected after delivery. */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /** Public secure tracking link for the client. */
    public function trackingUrl(): string
    {
        return route('projects.track', $this->token);
    }

    /** Human label for the current status. */
    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }
}
