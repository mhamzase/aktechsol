{{-- Row partial for the activity log AJAX table. --}}
@forelse ($logs as $log)
    <tr>
        <td data-label="When" class="whitespace-nowrap text-slate-500">{{ $log->created_at->format('d M Y, h:i A') }}</td>
        <td data-label="User" class="font-medium text-slate-700">{{ $log->user->name ?? 'System' }}</td>
        <td data-label="Action"><span class="badge badge-blue">{{ $log->action }}</span></td>
        <td data-label="Details" class="text-slate-500">{{ \Illuminate\Support\Str::limit($log->description, 80) }}</td>
        <td data-label="IP" class="text-slate-400 text-xs">{{ $log->ip_address }}</td>
    </tr>
@empty
    <tr><td colspan="5" class="text-center py-10 text-slate-400 text-sm">No activity recorded yet.</td></tr>
@endforelse
