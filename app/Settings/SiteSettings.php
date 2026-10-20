<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public ?string $site_name      = null;
    public ?string $site_email     = null;
    public ?string $site_phone     = null;
    public ?string $site_address   = null;
    public ?string $google_map_embed_url = null;

    // Homepage Stats
    public ?string $stat1_value = null;
    public ?string $stat1_label = null;
    public ?string $stat2_value = null;
    public ?string $stat2_label = null;
    public ?string $stat3_value = null;
    public ?string $stat3_label = null;

    // Popup & WhatsApp
    public ?string $popup_link_url = null;
    public ?string $popup_version = null;
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
    public ?string $policies_content = null;

    // SEO
    public ?string $seo_title          = null;
    public ?string $seo_description    = null;
    public ?string $seo_keywords       = null;
    public ?string $seo_canonical_url  = null;
    public ?string $seo_og_image       = null;
    public ?string $seo_robots         = null;

    // WhatsApp float button
    public ?string $whatsapp_number = null;
    public bool $whatsapp_enabled   = false;

    // Payment methods (manual invoicing) — [{name, details}]
    public array $payment_methods = [];

    // Default currency for new proposals/projects/invoices (PKR | USD)
    public ?string $default_currency = 'PKR';

    // Announcement popup
    public bool $popup_enabled      = false;
    public ?string $popup_title    = null;
    public ?string $popup_message  = null;
    public ?string $popup_btn_text = null;
    public ?string $popup_btn_url  = null;

    // Demo / intro video
    public ?string $demo_video_url   = null;
    public ?string $demo_video_title = null;

    // Brand message shown on homepage / about
    public ?string $brand_message = null;

    // Chatbot widget
    public bool $chatbot_enabled   = true;
    public ?string $chatbot_greeting = null;
    public ?string $chatbot_team_name = null;

    // Email (SMTP) — configured from admin Settings > Email tab.
    // Applied at runtime by App\Support\MailConfig.
    public ?string $mail_host         = null;
    public ?int $mail_port            = 587;
    public ?string $mail_username     = null;
    public ?string $mail_password     = null; // stored encrypted
    public ?string $mail_encryption   = 'tls';
    public ?string $mail_from_address = null;
    public ?string $mail_from_name    = null;
    // Address that receives website enquiries (quotes, contact, demos...)
    public ?string $notification_email = null;

    public static function group(): string
    {
        return 'site';
    }
}
