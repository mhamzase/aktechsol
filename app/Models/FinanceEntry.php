<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * FinanceEntry — one row of the company ledger (Finance section).
 *
 * type:   revenue | expense | withdrawal
 * source: manual (typed in by the admin) | project (created
 *         automatically when a project's COMPANY revenue share is
 *         marked paid; linked via revenue_share_id, never duplicated)
 *
 * Every entry snapshots the PKR exchange rate of its own day
 * (rate_to_pkr + amount_pkr) so the overall PKR totals stay stable
 * when the rates in Settings are later updated.
 */
class FinanceEntry extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const EXPENSE_CATEGORIES = ['Salary', 'Marketing', 'Tools & Software', 'Rent', 'Utilities', 'Travel', 'Other'];
    public const REVENUE_CATEGORIES = ['Service Income', 'Consultation', 'Product Sale', 'Other Income'];

    protected $fillable = [
        'type', 'source', 'revenue_share_id', 'title', 'category',
        'amount', 'currency', 'rate_to_pkr', 'amount_pkr', 'entry_date', 'note',
    ];

    protected $casts = [
        'amount' => 'float',
        'rate_to_pkr' => 'float',
        'amount_pkr' => 'float',
        'entry_date' => 'date',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachment')->singleFile();
    }

    public function revenueShare(): BelongsTo
    {
        return $this->belongsTo(ProjectRevenueShare::class, 'revenue_share_id');
    }

    /** True for auto entries that come from a project's company share. */
    public function isProject(): bool
    {
        return $this->source === 'project';
    }

    /** (Re)apply the PKR snapshot using the CURRENT Settings rate. */
    public function applySnapshot(): void
    {
        $rate = Money::pkrRate($this->currency);
        $this->rate_to_pkr = $rate;
        $this->amount_pkr = $rate === null ? null : round((float) $this->amount * $rate, 2);
    }

    /**
     * Create (once) the revenue entry for a PAID company share.
     * Idempotent: re-running never duplicates the entry.
     */
    public static function recordProjectRevenue(ClientProject $project, ProjectRevenueShare $share): void
    {
        $entry = self::firstOrNew(['revenue_share_id' => $share->id]);
        $entry->fill([
            'type' => 'revenue',
            'source' => 'project',
            'title' => 'Project revenue: ' . $project->title,
            'category' => null,
            'amount' => (float) $share->amount,
            'currency' => $project->currency ?? 'PKR',
            'entry_date' => ($share->paid_at ?? now())->toDateString(),
            'note' => 'Company share (' . rtrim(rtrim(number_format((float) $share->percentage, 2), '0'), '.') . '%) of ' . $project->title . '.',
        ]);
        $entry->applySnapshot();
        $entry->save();
    }

    /** Net balance in ONE currency: revenue − expenses − withdrawals. */
    public static function netBalance(string $currency): float
    {
        $sum = fn (string $type) => (float) self::where('type', $type)
            ->where('currency', Money::code($currency))->sum('amount');

        return $sum('revenue') - $sum('expense') - $sum('withdrawal');
    }

    /** Net balance converted to PKR via the entries' own snapshots. */
    public static function netBalancePkr(): float
    {
        $sum = fn (string $type) => (float) self::where('type', $type)->sum('amount_pkr');

        return $sum('revenue') - $sum('expense') - $sum('withdrawal');
    }
}
