<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

/**
 * UserController — admin user management (AJAX data-table + drawer forms).
 * Assign roles here; permissions come from the role.
 */
class UserController extends Controller
{
    /** Listing page; JSON rows for the shared AJAX data-table. */
    public function index(Request $request)
    {
        $query = User::with('roles')->latest();

        if ($search = $request->get('search')) {
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        $users = $query->paginate($request->get('per_page', 10));

        // The first-ever Administrator is fully protected: no edit, no delete.
        $protectedId = User::whereHas('roles', fn ($q) => $q->where('name', 'Administrator'))
            ->orderBy('id')->value('id');

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.users._rows', compact('users', 'protectedId'))->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $users])->render(),
                'stats'      => "Showing {$users->firstItem()}–{$users->lastItem()} of {$users->total()}",
            ]);
        }

        return view('admin.users.index');
    }

    /** Drawer: create form. */
    public function create()
    {
        return view('admin.users._form', [
            'user' => new User(),
            'roles' => Role::pluck('name', 'name'),
            'action' => route('admin.users.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => bcrypt($data['password']),
        ]);
        $user->syncRoles($data['roles'] ?? []);

        ActivityLog::record('user.created', $user, "User {$user->email} created.");

        return response()->json(['message' => 'User created successfully.']);
    }

    /** Drawer: edit form. */
    public function edit(User $user)
    {
        return view('admin.users._form', [
            'user' => $user->load('roles'),
            'roles' => Role::pluck('name', 'name'),
            'action' => route('admin.users.update', $user),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            ...(isset($data['password']) ? ['password' => bcrypt($data['password'])] : []),
        ]);

        // Never let the last Administrator lock themselves out.
        if ($user->is(auth()->user()) && ! in_array('Administrator', $data['roles'] ?? [])) {
            $data['roles'][] = 'Administrator';
        }
        $user->syncRoles($data['roles'] ?? []);

        ActivityLog::record('user.updated', $user, "User {$user->email} updated.");

        return response()->json(['message' => 'User updated successfully.']);
    }

    public function destroy(User $user)
    {
        if ($user->is(auth()->user())) {
            return response()->json(['message' => 'You cannot delete your own account.'], 422);
        }

        ActivityLog::record('user.deleted', $user, "User {$user->email} deleted.");
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
