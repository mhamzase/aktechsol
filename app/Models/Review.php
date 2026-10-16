<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * Review — client + team reviews collected after a project is delivered.
 * Clients submit via the secure /review/{token} link; admins moderate.
 * Approved reviews are published (is_published = true).
 */
class Review extends Model
{
    public const STATUSES = ['pending', 'approved', 'rejected'];

    protected $fillable = [
        'client_project_id', 'reviewer_type', 'reviewer_name',
        'rating', 'review', 'is_published', 'status', 'token',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'rating'       => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Review $review) {
            $review->token ??= Str::random(48);
            $review->status ??= 'pending';
        });
    }

    /** The project this review belongs to. */
    public function project(): BelongsTo
    {
        return $this->belongsTo(ClientProject::class, 'client_project_id');
    }

    /** Human-readable status. */
    public function statusLabel(): string
    {
        return ucfirst($this->status);
    }

    /** Scope: approved (published) reviews. */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /** Scope: pending moderation. */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /** Secure client review link. */
    public function reviewUrl(): string
    {
        return route('reviews.form', $this->token);
    }

    /** Star rating HTML (for admin display). */
    public function starsHtml(): string
    {
        $out = '';
        for ($i = 1; $i <= 5; $i++) {
            $fill = $i <= $this->rating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600';
            $out .= '<svg class="w-4 h-4 ' . $fill . '" fill="currentColor" viewBox="0 0 20 20"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.29 3.96a1 1 0 00.95.69h4.16c.97 0 1.37 1.24.59 1.81l-3.37 2.45a1 1 0 00-.36 1.12l1.28 3.95c.3.93-.75 1.7-1.54 1.13l-3.36-2.44a1 1 0 00-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.53-1.13l1.28-3.95a1 1 0 00-.37-1.12L2.07 9.39c-.78-.57-.38-1.81.6-1.81h4.15a1 1 0 00.95-.69l1.28-3.96z"/></svg>';
        }
        return $out;
    }
}
