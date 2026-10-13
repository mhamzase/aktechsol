<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * ActivityLog — simple audit trail for admin actions.
 */
class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'subject_type', 'subject_id',
        'description', 'ip_address',
    ];

    /** The admin user who performed the action. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The subject the action was performed on (polymorphic). */
    public function subject(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'subject_type', 'subject_id');
    }

    /**
     * Record an activity entry.
     */
    public static function record(string $action, ?Model $subject = null, ?string $description = null): self
    {
        return self::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'subject_type'=> $subject ? get_class($subject) : null,
            'subject_id'  => $subject?->getKey(),
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}
