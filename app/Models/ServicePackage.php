<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ServicePackage — a pricing tier (e.g. Starter / Growth / Pro).
 * Shown on the website only when is_active is true.
 */
class ServicePackage extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'price_label',
        'turnaround', 'features', 'is_popular', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features'   => 'array',
        'price'      => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active'  => 'boolean',
    ];

    /** Packages visible on the website, in display order. */
    public static function visible()
    {
        return self::where('is_active', true)->orderBy('sort_order');
    }
}
