<?php

namespace Database\Seeders;

use App\Models\SiteAsset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Create default site asset for media
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
