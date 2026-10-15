<?php

namespace App\Concerns;

use App\Support\LogActivity;

/**
 * LogsActivity — attach to any admin-managed model for automatic
 * activity log entries on create / update / delete.
 *
 * Add `use LogsActivity;` to the model. Logging is skipped when
 * no user is authenticated (e.g. seeders, queue workers).
 */
trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(fn ($model) => LogActivity::record('created', $model));

        static::updated(function ($model) {
            // Skip no-op saves (e.g. touch()).
            if ($model->wasChanged()) {
                LogActivity::record('updated', $model);
            }
        });

        static::deleted(fn ($model) => LogActivity::record('deleted', $model));
    }
}
