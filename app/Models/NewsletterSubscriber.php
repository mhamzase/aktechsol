<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterSubscriber extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'email',
        'is_subscribed',
    ];

    protected $casts = [
        'is_subscribed' => 'boolean',
    ];

    public function scopeSubscribed($query)
    {
        return $query->where('is_subscribed', true);
    }
}
