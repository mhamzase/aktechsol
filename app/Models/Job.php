<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Job — a job or internship listing.
 */
class Job extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'type', 'location_type',
        'description', 'requirements', 'is_open', 'sort_order',
    ];

    protected $casts = [
        'is_open' => 'boolean',
    ];

    /** Applications submitted for this listing. */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /** Listings visible on the website, in display order. */
    public static function visible()
    {
        return self::where('is_open', true)->orderBy('sort_order');
    }
}
