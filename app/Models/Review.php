<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Review — client + team reviews collected after a project is delivered.
 * Only published reviews appear on the website.
 */
class Review extends Model
{
    protected $fillable = [
        'client_project_id', 'reviewer_type', 'reviewer_name',
        'rating', 'review', 'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /** The project this review belongs to. */
    public function project(): BelongsTo
    {
        return $this->belongsTo(ClientProject::class, 'client_project_id');
    }
}
