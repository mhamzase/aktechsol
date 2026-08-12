<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@test.com')],
            [
                'name' => env('ADMIN_NAME', 'Admin User'),
                'password' => bcrypt(env('ADMIN_PASSWORD', 'password')), // change after install
            ]
        );

        $user->assignRole('Administrator');
    }
}
