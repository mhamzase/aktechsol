{{-- Drawer form: create / edit user. Submitted via AJAX (data-ajax-form). --}}
<form data-ajax-form data-reload-table="users-table" method="POST" action="{{ $action }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div class="space-y-4">
        {{-- Avatar (Spatie Media Library) --}}
        <div class="flex items-center gap-4">
            <span data-avatar-preview class="w-16 h-16 rounded-full overflow-hidden bg-gradient-to-br from-[#1669d9] to-[#0d3a7a] text-white text-xl font-bold flex items-center justify-center shrink-0 shadow-md">
                @if ($user->getAvatarUrl())
                    <img src="{{ $user->getAvatarUrl() }}" alt="" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($user->name ?: 'U', 0, 1)) }}
                @endif
            </span>
            <div class="min-w-0 flex-1">
                <label class="ui-label">Profile Photo</label>
                <div class="flex items-center gap-2">
                    <label class="btn btn-secondary !py-2 !px-3 !text-xs cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Choose photo
                        <input type="file" name="avatar" accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden" data-avatar-input>
                    </label>
                    @if ($user->getAvatarUrl())
                        <button type="button" class="btn !py-2 !px-3 !text-xs !bg-red-50 !text-red-600 hover:!bg-red-100" data-avatar-remove>Remove</button>
                        <input type="hidden" name="remove_avatar" value="0" data-avatar-remove-flag>
                    @endif
                </div>
                <p class="ui-hint mt-1">JPG, PNG or WebP — max 2MB.</p>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <x-ui.input name="name" label="Name" :value="old('name', $user->name)" required />
            <x-ui.input name="email" type="email" label="Email" :value="old('email', $user->email)" required />
        </div>
        <x-ui.input name="phone" label="Phone" :value="old('phone', $user->phone)" placeholder="+92 300 0000000" />

        <div class="grid sm:grid-cols-2 gap-4">
            <x-ui.password name="password" label="Password" :required="!$user->exists"
                           :hint="$user->exists ? 'Leave blank to keep the current password.' : 'Minimum 8 characters.'" />
            <x-ui.password name="password_confirmation" label="Confirm Password" />
        </div>

        <div>
            <span class="ui-label">Roles</span>
            <div class="space-y-1 rounded-xl border border-slate-200 p-3 dark:border-white/10">
                @foreach ($roles as $role)
                    <x-ui.checkbox name="roles[]" :value="$role" :label="$role"
                                   :checked="$user->roles->contains('name', $role)" />
                @endforeach
            </div>
            <p class="ui-hint mt-1">Permissions come from the assigned roles.</p>
        </div>

        <x-admin.drawer-footer :save-label="$user->exists ? 'Save Changes' : 'Create User'" />
    </div>
</form>
