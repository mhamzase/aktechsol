<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Invoice — a milestone / project invoice.
 * Payments are manual: the client uploads a screenshot proof
 * and admin verifies it.
 */
class Invoice extends Model
{
    use SoftDeletes;

    /** Available statuses. */
    public const STATUSES = ['unpaid', 'partially_paid', 'paid', 'overdue', 'cancelled'];

    protected $fillable = [
        'client_project_id', 'invoice_no', 'title',
        'amount', 'paid_amount', 'due_date', 'status', 'notes',
    ];

    protected $casts = [
        'due_date'    => 'date',
        'amount'      => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /** Auto-generate a readable invoice number on creation. */
    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (! $invoice->invoice_no) {
                $invoice->invoice_no = 'INV-' . now()->format('Ym') . '-'
                    . str_pad((string) (self::withTrashed()->count() + 1), 4, '0', STR_PAD_LEFT);
            }
        });
    }

    /** The project this invoice belongs to (optional). */
    public function project(): BelongsTo
    {
        return $this->belongsTo(ClientProject::class, 'client_project_id');
    }

    /** Payment proofs uploaded by the client. */
    public function payments(): HasMany
    {
        return $this->hasMany(InvoicePayment::class);
    }

    /** Remaining balance. */
    public function balance(): float
    {
        return (float) $this->amount - (float) $this->paid_amount;
    }
}
