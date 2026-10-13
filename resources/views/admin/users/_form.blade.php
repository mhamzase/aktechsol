{{-- Drawer form: create / edit user. Submitted via AJAX (data-ajax-form). --}}
<form data-ajax-form data-reload-table="users-table" method="POST" action="{{ $action }}">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div class="space-y-4">
        <div>
            <label class="form-label">Name *</label>
            <input name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div>
            <label class="form-label">Email *</label>
            <input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div>
            <label class="form-label">Phone</label>
            <input name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
        </div>
        <div>
            <label class="form-label">Password {{ $user->exists ? '(leave blank to keep)' : '*' }}</label>
            <input name="password" type="password" class="form-control" autocomplete="new-password" {{ $user->exists ? '' : 'required' }}>
        </div>
        <div>
            <label class="form-label">Confirm Password</label>
            <input name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
        </div>
        <div>
            <label class="form-label">Roles</label>
            <div class="space-y-2 rounded-lg border border-slate-200 p-3">
                @foreach ($roles as $role)
                    <label class="flex items-center gap-2 text-sm text-slate-600">
                        <input type="checkbox" name="roles[]" value="{{ $role }}"
                               @checked($user->roles->contains('name', $role))
                               class="w-4 h-4 accent-[#1d4ed8]">
                        {{ $role }}
                    </label>
                @endforeach
            </div>
            <p class="text-xs text-slate-400 mt-1">Permissions come from the assigned roles.</p>
        </div>
        <button type="submit" class="btn btn-primary w-full">{{ $user->exists ? 'Update User' : 'Create User' }}</button>
    </div>
</form>
