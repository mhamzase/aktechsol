{{-- Row partial for the users AJAX table. Every <td> carries data-label for the mobile card view. --}}
@forelse ($users as $user)
    <tr>
        <td data-label="User">
            <span class="flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-full bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white text-xs font-bold flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
                <span class="font-semibold text-slate-800">{{ $user->name }}</span>
            </span>
        </td>
        <td data-label="Email" class="text-slate-500">{{ $user->email }}</td>
        <td data-label="Phone" class="text-slate-500">{{ $user->phone ?? '—' }}</td>
        <td data-label="Roles">
            @forelse ($user->roles as $role)
                <span class="badge {{ $role->name === 'Administrator' ? 'badge-blue' : 'badge-gray' }}">{{ $role->name }}</span>
            @empty
                <span class="text-slate-300 text-xs">No role</span>
            @endforelse
        </td>
        <td data-label="Joined" class="text-slate-500 whitespace-nowrap">{{ $user->created_at->format('d M Y') }}</td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <button class="btn btn-secondary !py-1.5 !px-3 !text-xs" data-action="edit" data-id="{{ $user->id }}">Edit</button>
            @if (!$user->is(auth()->user()))
                <button class="btn btn-danger !py-1.5 !px-3 !text-xs" data-action="delete" data-id="{{ $user->id }}">Delete</button>
            @endif
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No users found.</td></tr>
@endforelse
