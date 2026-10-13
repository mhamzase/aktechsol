<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * DemoBooking — a "Book a Demo" request from the website.
 */
class DemoBooking extends Model
{
    /** Available statuses. */
    public const STATUSES = ['new', 'contacted', 'scheduled', 'done', 'cancelled'];

    protected $fillable = [
        'name', 'email', 'phone', 'service',
        'preferred_date', 'preferred_time', 'message', 'status',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];
}
