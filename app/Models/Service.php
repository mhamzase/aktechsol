<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;

class Service extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'short_description',
        'full_description',
        'icon',
        'sort_order',
        'status',
        'seo_title',
        'meta_description',
        'canonical_url',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function (Service $service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
        static::updating(function (Service $service) {
            if (empty($service->slug)) {
                $service->slug = Str::slug($service->title);
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('service_image')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->getFirstMediaUrl('service_image');
    }

    // Scope for active services
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
