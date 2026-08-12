<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SiteAsset extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['name'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml']);

        $this->addMediaCollection('favicon')
             ->singleFile()
             ->acceptsMimeTypes(['image/x-icon', 'image/vnd.microsoft.icon', 'image/png', 'image/svg+xml']);
    }

    // Helper to get logo URL
    public function getLogoUrl(): ?string
    {
        return $this->getFirstMediaUrl('logo');
    }

    // Helper to get favicon URL
    public function getFaviconUrl(): ?string
    {
        return $this->getFirstMediaUrl('favicon');
    }
}
