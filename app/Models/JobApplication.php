<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * JobApplication — an application for a job / internship.
 * A portfolio URL is required: applications without work
 * samples are not reviewed.
 */
class JobApplication extends Model implements HasMedia
{
    use InteractsWithMedia;

    /** Available statuses. */
    public const STATUSES = ['new', 'reviewed', 'shortlisted', 'rejected'];

    protected $fillable = [
        'job_id', 'name', 'email', 'phone',
        'portfolio_url', 'cover_note', 'status',
    ];

    /** The listing applied for. */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    /** Optional resume / CV upload. */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('resume')
            ->singleFile()
            ->acceptsMimeTypes(['application/pdf']);
    }
}
