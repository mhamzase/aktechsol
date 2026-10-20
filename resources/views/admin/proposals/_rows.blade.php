{{-- Row partial for the proposals AJAX table. --}}
@php
    $statusBadges = [
        'draft' => 'badge-gray', 'sent' => 'badge-blue', 'accepted' => 'badge-green',
        'rejected' => 'badge-red', 'changes_requested' => 'badge-amber', 'expired' => 'badge-gray',
    ];
@endphp
@forelse ($proposals as $proposal)
    <tr>
        <td data-label="Title">
            <p class="font-medium text-slate-800">{{ $proposal->title }}</p>
            <p class="text-xs text-slate-400">{{ Str::limit(strip_tags($proposal->scope), 60) }}</p>
        </td>
        <td data-label="Client">
            <p class="font-medium text-slate-800">{{ $proposal->client_name }}</p>
            <p class="text-xs text-slate-400">{{ $proposal->client_email }}</p>
        </td>
        <td data-label="Amount" class="font-semibold text-slate-800 whitespace-nowrap">{{ \App\Support\Money::format($proposal->amount, $proposal->currency) }}</td>
        <td data-label="Status"><span class="badge {{ $statusBadges[$proposal->status] ?? 'badge-gray' }}">{{ ucfirst(str_replace('_', ' ', $proposal->status)) }}</span></td>
        <td data-label="Sent" class="text-slate-500 whitespace-nowrap">{{ $proposal->sent_at?->format('M d, Y') ?? '—' }}</td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.proposals.show', $proposal)"
                :editUrl="route('admin.proposals.edit', $proposal)"
                :editWide="true"
                :deleteUrl="route('admin.proposals.destroy', $proposal)"
                tableId="proposals-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No proposals found.</td></tr>
@endforelse
