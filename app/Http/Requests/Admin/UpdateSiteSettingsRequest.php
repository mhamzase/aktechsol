<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiteSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Basic
            'site_name'       => ['required', 'string', 'max:255'],
            'site_email'      => ['required', 'email', 'max:255'],
            'site_phone'      => ['nullable', 'string', 'max:50'],
            'site_address'    => ['nullable', 'string', 'max:500'],
            'footer_text'     => ['nullable', 'string', 'max:500'],
            'copyright_text'  => ['nullable', 'string', 'max:255'],
            'facebook_url'    => ['nullable', 'url', 'max:255'],
            'twitter_url'     => ['nullable', 'url', 'max:255'],
            'linkedin_url'    => ['nullable', 'url', 'max:255'],
            'instagram_url'   => ['nullable', 'url', 'max:255'],
            'logo'            => ['nullable', 'image', 'mimes:jpeg,png,webp,svg', 'max:2048'],
            'favicon'         => ['nullable', 'image', 'mimes:ico,png,svg', 'max:1024'],

            // About
            'about_hero_title'       => ['nullable', 'string', 'max:255'],
            'about_hero_subtitle'    => ['nullable', 'string', 'max:500'],
            'about_intro_title'      => ['nullable', 'string', 'max:255'],
            'about_intro_text'       => ['nullable', 'string', 'max:2000'],
            'about_mission_title'    => ['nullable', 'string', 'max:255'],
            'about_mission_subtitle' => ['nullable', 'string', 'max:500'],
            'about_why_title'        => ['nullable', 'string', 'max:255'],

            'about_mission_card1_title' => ['nullable', 'string', 'max:255'],
            'about_mission_card1_text'  => ['nullable', 'string', 'max:500'],
            'about_mission_card2_title' => ['nullable', 'string', 'max:255'],
            'about_mission_card2_text'  => ['nullable', 'string', 'max:500'],
            'about_mission_card3_title' => ['nullable', 'string', 'max:255'],
            'about_mission_card3_text'  => ['nullable', 'string', 'max:500'],

            'about_why_item1_title' => ['nullable', 'string', 'max:255'],
            'about_why_item1_text'  => ['nullable', 'string', 'max:500'],
            'about_why_item2_title' => ['nullable', 'string', 'max:255'],
            'about_why_item2_text'  => ['nullable', 'string', 'max:500'],
            'about_why_item3_title' => ['nullable', 'string', 'max:255'],
            'about_why_item3_text'  => ['nullable', 'string', 'max:500'],
            'about_why_item4_title' => ['nullable', 'string', 'max:255'],
            'about_why_item4_text'  => ['nullable', 'string', 'max:500'],

            // Legal Pages
            'privacy_policy_content'   => ['nullable', 'string'],
            'terms_conditions_content' => ['nullable', 'string'],
        ];
    }
}
