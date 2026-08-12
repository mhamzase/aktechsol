<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'order' => 1, 'slug' => 'web-development'],
            ['name' => 'Mobile App Development', 'order' => 2, 'slug' => 'mobile-app-development'],
            ['name' => 'UI/UX Design', 'order' => 3, 'slug' => 'ui-ux-design'],
            ['name' => 'Cloud Solutions', 'order' => 4, 'slug' => 'cloud-solutions'],
        ];

        foreach ($categories as $data) {
            ServiceCategory::firstOrCreate(
                ['name' => $data['name']],
                ['order' => $data['order'], 'slug' => $data['slug'], 'status' => true]
            );
        }
    }
}
