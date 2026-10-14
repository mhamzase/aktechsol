<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Roles + permissions for the admin panel.
 *
 * Roles:
 *   - Administrator : full access (all permissions)
 *   - Team Member   : works on assigned client projects, answers chatbot
 */
class RoleSeeder extends Seeder
{
    /** All permissions used across the admin panel. */
    public const PERMISSIONS = [
        // System
        'manage users',
        'manage roles',
        'manage settings',
        'view activity log',
        // Sales flow
        'manage quotes',
        'manage proposals',
        // Work flow
        'manage projects',
        'manage invoices',
        'manage reviews',
        // Engagement
        'manage chatbot',
        'manage demo bookings',
        'manage contact messages',
        'manage newsletter',
        'manage job applications',
        // Content
        'manage services',
        'manage portfolio',
        'manage testimonials',
        'manage blog',
        'manage faqs',
        'manage jobs',
        'manage packages',
        'manage pages',
        'manage media',
    ];

    public function run(): void
    {
        foreach (self::PERMISSIONS as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'Administrator', 'guard_name' => 'web']);
        $admin->syncPermissions(self::PERMISSIONS);

        $member = Role::firstOrCreate(['name' => 'Team Member', 'guard_name' => 'web']);
        // Team Member is a status label only — permissions are assigned
        // USER-WISE (direct permissions), never on this role.
        $member->syncPermissions([]);
    }
}
