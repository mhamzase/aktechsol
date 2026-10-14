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

        $this->addMediaCollection('popup_image')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('seo_og_image')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
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

    // Helper to get popup image URL
    public function getPopupImageUrl(): ?string
    {
        return $this->getFirstMediaUrl('popup_image');
    }

    // Helper to get SEO OG image URL
    public function getSeoOgImageUrl(): ?string
    {
        return $this->getFirstMediaUrl('seo_og_image');
    }
}
