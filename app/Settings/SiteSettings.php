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

    // About Page
    public ?string $about_hero_title      = null;
    public ?string $about_hero_subtitle   = null;
    public ?string $about_intro_title     = null;
    public ?string $about_intro_text      = null;
    public ?string $about_mission_title   = null;
    public ?string $about_mission_subtitle = null;
    public ?string $about_why_title       = null;

    public ?string $about_mission_card1_title = null;
    public ?string $about_mission_card1_text  = null;
    public ?string $about_mission_card2_title = null;
    public ?string $about_mission_card2_text  = null;
    public ?string $about_mission_card3_title = null;
    public ?string $about_mission_card3_text  = null;

    public ?string $about_why_item1_title = null;
    public ?string $about_why_item1_text  = null;
    public ?string $about_why_item2_title = null;
    public ?string $about_why_item2_text  = null;
    public ?string $about_why_item3_title = null;
    public ?string $about_why_item3_text  = null;
    public ?string $about_why_item4_title = null;
    public ?string $about_why_item4_text  = null;

    // Legal Pages
    public ?string $privacy_policy_content = null;
    public ?string $terms_conditions_content = null;

    public static function group(): string
    {
        return 'site';
    }
}
