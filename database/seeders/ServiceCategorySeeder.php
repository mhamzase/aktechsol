<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Web Development', 'sort_order' => 1, 'slug' => 'web-development'],
            ['name' => 'Mobile App Development', 'sort_order' => 2, 'slug' => 'mobile-app-development'],
            ['name' => 'UI/UX Design', 'sort_order' => 3, 'slug' => 'ui-ux-design'],
            ['name' => 'Cloud Solutions', 'sort_order' => 4, 'slug' => 'cloud-solutions'],
        ];

        foreach ($categories as $data) {
            ServiceCategory::firstOrCreate(
                ['name' => $data['name']],
                ['sort_order' => $data['sort_order'], 'slug' => $data['slug'], 'status' => true]
            );
        }
    }
}
