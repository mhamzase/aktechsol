<?php

namespace Database\Seeders;

use App\Models\SiteAsset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        SiteAsset::firstOrCreate(['name' => 'default']);

        $settings = [
            'site_name'      => 'AK Tech SOL',
            'site_email'     => 'info@aktechsol.com',
            'site_phone'     => null,
            'site_address'   => null,
            'footer_text'    => null,
            'copyright_text' => null,
            'facebook_url'   => null,
            'twitter_url'    => null,
            'linkedin_url'   => null,
            'instagram_url'  => null,

            'about_hero_title'      => 'About Us',
            'about_hero_subtitle'   => 'We are a team of passionate developers, designers, and problem solvers dedicated to helping businesses grow through innovative digital solutions.',
            'about_intro_title'     => 'Who We Are',
            'about_intro_text'      => 'AK Tech SOL is a full-service software and freelancing agency focused on delivering high-quality web and mobile solutions.',
            'about_mission_title'   => 'Our Mission & Values',
            'about_mission_subtitle'=> 'We believe in transparency, quality, and delivering value at every step.',
            'about_why_title'       => 'Why Choose Us',

            'about_mission_card1_title' => 'Innovation',
            'about_mission_card1_text'  => 'We embrace new technologies and creative approaches.',
            'about_mission_card2_title' => 'Security',
            'about_mission_card2_text'  => 'We build robust and secure applications.',
            'about_mission_card3_title' => 'Trust',
            'about_mission_card3_text'  => 'Long-term partnerships built on reliability.',

            'about_why_item1_title' => 'Experienced Team',
            'about_why_item1_text'  => 'Our team has years of experience.',
            'about_why_item2_title' => 'On-Time Delivery',
            'about_why_item2_text'  => 'We respect deadlines.',
            'about_why_item3_title' => 'Dedicated Support',
            'about_why_item3_text'  => 'We provide ongoing maintenance.',
            'about_why_item4_title' => 'Result Oriented',
            'about_why_item4_text'  => 'We focus on measurable outcomes.',

            // Legal pages default content
            'privacy_policy_content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This policy explains how we collect, use, and protect your information.</p>',
            'terms_conditions_content' => '<h2>Terms &amp; Conditions</h2><p>By using our services, you agree to the following terms and conditions.</p>',
        ];

        foreach ($settings as $property => $value) {
            DB::table('settings')->insert([
                'group'      => 'site',
                'name'       => $property,
                'payload'    => json_encode($value),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
