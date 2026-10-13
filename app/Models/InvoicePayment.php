<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * InvoicePayment — a client payment proof (screenshot upload).
 * Admin verifies or rejects it manually.
 */
class InvoicePayment extends Model implements HasMedia
{
    use InteractsWithMedia;

    /** Available statuses. */
    public const STATUSES = ['pending', 'verified', 'rejected'];

    protected $fillable = [
        'invoice_id', 'amount', 'method', 'reference',
        'status', 'verified_by', 'verified_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /** The invoice this payment is for. */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /** Admin who verified the payment. */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /** Single screenshot proof image. */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('screenshot')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}
