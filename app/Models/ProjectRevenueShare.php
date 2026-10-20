<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * ProjectRevenueShare — one person's cut of a delivered project's
 * received payment. A null user_id is the company's own share;
 * member_name is snapshotted so the record stays readable even if
 * the user account is later deleted. The payment proof screenshot
 * (required when marking paid) lives in Spatie Media Library.
 */
class ProjectRevenueShare extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const STATUSES = ['pending', 'paid'];

    protected $fillable = [
        'client_project_id', 'user_id', 'member_name',
        'percentage', 'amount', 'status', 'paid_at',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'amount'     => 'decimal:2',
        'paid_at'    => 'datetime',
    ];

    /** The project this share belongs to. */
    public function project(): BelongsTo
    {
        return $this->belongsTo(ClientProject::class, 'client_project_id');
    }

    /** The team member (null for the company's share). */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isCompany(): bool
    {
        return $this->user_id === null;
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /** Payment proof screenshot, attached when the share is marked paid. */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('payment_proof')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
