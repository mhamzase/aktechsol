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
        'Engagement' => ['manage chatbot', 'manage demo bookings', 'manage contact messages', 'manage newsletter', 'manage job applications'],
        'Content'    => ['manage services', 'manage portfolio', 'manage testimonials', 'manage blog', 'manage faqs', 'manage jobs', 'manage packages', 'manage pages', 'manage media'],
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
