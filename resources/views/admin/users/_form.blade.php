{{-- Drawer form: create / edit user. Submitted via AJAX (data-ajax-form). --}}
<form data-ajax-form data-reload-table="users-table" method="POST" action="{{ $action }}" novalidate>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div class="space-y-4">
        <x-ui.input name="name" label="Name" :value="old('name', $user->name)" required />
        <x-ui.input name="email" type="email" label="Email" :value="old('email', $user->email)" required />
        <x-ui.input name="phone" label="Phone" :value="old('phone', $user->phone)" placeholder="+92 300 0000000" />

        <x-ui.password name="password" label="Password" :required="!$user->exists"
                       :hint="$user->exists ? 'Leave blank to keep the current password.' : 'Minimum 8 characters.'" />
        <x-ui.password name="password_confirmation" label="Confirm Password" />

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

        <button type="submit" class="btn btn-primary w-full">{{ $user->exists ? 'Update User' : 'Create User' }}</button>
    </div>
</form>
