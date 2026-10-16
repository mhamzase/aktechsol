<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'client_project_id', 'invoice_no', 'token', 'title',
        'amount', 'paid_amount', 'due_date', 'status', 'notes',
    ];

    protected $casts = [
        'due_date'    => 'date',
        'amount'      => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /** Auto-generate invoice number + secure token on creation. */
    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (! $invoice->invoice_no) {
                $invoice->invoice_no = 'INV-' . now()->format('Ym') . '-'
                    . str_pad((string) (self::withTrashed()->count() + 1), 4, '0', STR_PAD_LEFT);
            }
            $invoice->token ??= Str::random(48);
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

    /** Alias for balance (template-friendly). */
    public function remainingAmount(): float
    {
        return $this->balance();
    }

    /** Human-readable status. */
    public function statusLabel(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /** True when past due and not fully paid/cancelled. */
    public function isOverdue(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && ! in_array($this->status, ['paid', 'cancelled']);
    }

    /** Secure client URL. */
    public function invoiceUrl(): string
    {
        return route('invoices.show', $this->token);
    }

    /** Client name: from project or stored on... falls back gracefully. */
    public function clientName(): string
    {
        return $this->project?->client_name ?? '—';
    }

    /** Client email from project. */
    public function clientEmail(): ?string
    {
        return $this->project?->client_email;
    }

    /** Recompute status from paid_amount (keeps 'cancelled' untouched). */
    public function refreshPaymentStatus(): void
    {
        if ($this->status === 'cancelled') return;

        $paid = (float) $this->paid_amount;
        $total = (float) $this->amount;

        if ($paid >= $total && $total > 0) {
            $this->status = 'paid';
        } elseif ($paid > 0) {
            $this->status = 'partially_paid';
        } elseif ($this->isOverdue()) {
            $this->status = 'overdue';
        } else {
            $this->status = 'unpaid';
        }
        $this->save();
    }

    /** Scope: invoices that should be flipped to overdue. */
    public function scopeNeedsOverdue($query)
    {
        return $query->whereNotIn('status', ['paid', 'cancelled', 'overdue'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', today());
    }
}
