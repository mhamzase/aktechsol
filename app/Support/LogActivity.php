<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * LogActivity — central helper for the admin activity log.
 *
 * Usage:
 *   LogActivity::record('created', $service, "Created service: {$service->title}");
 *
 * Models can also use the LogsActivity trait for automatic
 * created/updated/deleted logging.
 */
class LogActivity
{
    public static function record(string $action, ?Model $subject = null, ?string $description = null): void
    {
        // Only log when there's an authenticated user (admin panel context).
        if (!Auth::check()) {
            return;
        }

        try {
            ActivityLog::create([
                'user_id'      => Auth::id(),
                'action'       => $action,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id'   => $subject?->getKey(),
                'description'  => $description ?? self::defaultDescription($action, $subject),
                'ip_address'   => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // Never break the main flow because logging failed.
            report($e);
        }
    }

    protected static function defaultDescription(string $action, ?Model $subject): string
    {
        if (!$subject) {
            return ucfirst($action);
        }

        $name = class_basename($subject);
        $label = $subject->title ?? $subject->name ?? $subject->question ?? $subject->client_name ?? "#{$subject->getKey()}";

        return "{$action} {$name}: {$label}";
    }
}
