{{-- Row partial for the quotes AJAX table. --}}
@php
    $statusBadges = ['new' => 'badge-amber', 'reviewed' => 'badge-blue', 'quoted' => 'badge-green', 'rejected' => 'badge-red'];
@endphp
@forelse ($quotes as $quote)
    <tr>
        <td data-label="Name">
            <p class="font-medium text-slate-800">{{ $quote->name }}</p>
            <p class="text-xs text-slate-400">{{ $quote->email }}</p>
        </td>
        <td data-label="Service" class="text-slate-500">{{ $quote->service?->title ?? '—' }}</td>
        <td data-label="Budget" class="text-slate-500">{{ $quote->budget_range ?? '—' }}</td>
        <td data-label="Status"><span class="badge {{ $statusBadges[$quote->status] ?? 'badge-gray' }}">{{ ucfirst($quote->status) }}</span></td>
        <td data-label="Received" class="text-slate-500 whitespace-nowrap">{{ $quote->created_at->format('M d, Y') }}</td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.quotes.show', $quote)"
                :deleteUrl="route('admin.quotes.destroy', $quote)"
                tableId="quotes-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No quote requests found.</td></tr>
@endforelse
