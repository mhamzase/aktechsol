<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

/**
 * User-wise direct permissions for Team Members.
 *
 * The "Team Member" role is a status label only — it carries NO permissions.
 * Every permission here is stored DIRECTLY on the user
 * (Spatie's model_has_permissions), never on the role.
 */
class UserPermissionController extends Controller
{
    /** Permission groups for the drawer UI. */
    public const GROUPS = [
        'System'     => ['manage users', 'manage roles', 'manage settings', 'view activity log'],
        'Sales'      => ['manage quotes', 'manage proposals'],
        'Work'       => ['manage projects', 'manage invoices', 'manage reviews'],
        'Finance'    => ['manage finance'],
        'Engagement' => ['manage chatbot', 'manage demo bookings', 'manage contact messages', 'manage newsletter', 'manage job applications'],
        'Content'    => ['manage services', 'manage portfolio', 'manage testimonials', 'manage blog', 'manage faqs', 'manage jobs', 'manage pages', 'manage media'],
    ];

    /** Which sidebar tab each permission unlocks (shown as a chip in the drawer). */
    public const SIDEBAR_MAP = [
        'manage users'           => 'Users & Roles',
        'manage roles'           => 'Users & Roles',
        'manage settings'        => 'Settings',
        'view activity log'      => 'Activity Log',
        'manage quotes'          => 'Quote Requests',
        'manage proposals'       => 'Proposals',
        'manage projects'        => 'Client Projects',
        'manage invoices'        => 'Invoices',
        'manage finance'         => 'Finance',
        'manage reviews'         => 'Reviews',
        'manage chatbot'         => 'Chatbot',
        'manage demo bookings'   => 'Demo Bookings',
        'manage contact messages'=> 'Contact Messages',
        'manage newsletter'      => 'Newsletter',
        'manage job applications'=> 'Job Applications',
        'manage services'        => 'Services',
        'manage portfolio'       => 'Portfolio',
        'manage testimonials'    => 'Testimonials',
        'manage blog'            => 'Blog',
        'manage faqs'            => 'FAQs',
        'manage jobs'            => 'Jobs',
        'manage pages'           => 'Pages',
        'manage media'           => 'Media Library',
    ];

    /** SVG icon paths per group. */
    public const GROUP_ICONS = [
        'System'     => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'Sales'      => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
        'Work'       => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'Finance'    => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
        'Engagement' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        'Content'    => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
    ];

    /** Drawer: permission checklist for a user. */
    public function edit(User $user)
    {
        // Ensure every known permission exists (in case the seeder hasn't run).
        foreach (RoleSeeder::PERMISSIONS as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        return view('admin.users._permissions', [
            'user'      => $user,
            'groups'    => self::GROUPS,
            'icons'     => self::GROUP_ICONS,
            'sidebarMap'=> self::SIDEBAR_MAP,
            'direct'    => $user->getDirectPermissions()->pluck('name')->all(),
            'action'    => route('admin.users.permissions.update', $user),
        ]);
    }

    /** Sync the user's DIRECT permissions (role permissions untouched). */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'permissions'   => 'nullable|array',
            'permissions.*' => 'in:' . implode(',', RoleSeeder::PERMISSIONS),
        ]);

        $user->syncPermissions($data['permissions'] ?? []);

        ActivityLog::record('user.permissions_updated', $user, "Permissions updated for {$user->email}.");

        return response()->json(['message' => 'Permissions updated successfully.']);
    }
}
