<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;

class Project extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'short_description', 'full_description',
        'client_name', 'project_url', 'completion_date', 'sort_order',
        'status', 'seo_title', 'meta_description', 'canonical_url',
    ];

    protected $casts = [
        'status' => 'boolean',
        'completion_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function (Project $project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
        static::updating(function (Project $project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title);
            }
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('gallery')
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function getFeaturedImageUrl(): ?string
    {
        return $this->getFirstMediaUrl('featured_image');
    }

    public function getGalleryImages()
    {
        return $this->getMedia('gallery');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
