<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * ProjectUpdate — a timeline entry on a client project.
 * Visible on the client's tracking link when is_client_visible is true.
 */
class ProjectUpdate extends Model
{
    protected $fillable = [
        'client_project_id', 'title', 'note', 'created_by', 'is_client_visible',
    ];

    protected $casts = [
        'is_client_visible' => 'boolean',
    ];

    /** The project this update belongs to. */
    public function project(): BelongsTo
    {
        return $this->belongsTo(ClientProject::class, 'client_project_id');
    }

    /** Team member who posted the update. */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
