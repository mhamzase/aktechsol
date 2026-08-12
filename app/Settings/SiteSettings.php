<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public ?string $site_name      = null;
    public ?string $site_email     = null;
    public ?string $site_phone     = null;
    public ?string $site_address   = null;
    public ?string $footer_text    = null;
    public ?string $copyright_text = null;
    public ?string $facebook_url   = null;
    public ?string $twitter_url    = null;
    public ?string $linkedin_url   = null;
    public ?string $instagram_url  = null;

    public static function group(): string
    {
        return 'site';
    }
}
