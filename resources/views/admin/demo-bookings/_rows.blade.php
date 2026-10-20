{{-- Row partial for the demo bookings AJAX table. --}}
@php
    $statusBadges = ['new' => 'badge-amber', 'contacted' => 'badge-blue', 'scheduled' => 'badge-purple', 'done' => 'badge-green', 'cancelled' => 'badge-red'];
@endphp
@forelse ($bookings as $booking)
    <tr>
        <td data-label="Name">
            <p class="font-medium text-slate-800">{{ $booking->name }}</p>
            <p class="text-xs text-slate-400">{{ $booking->email }}</p>
        </td>
        <td data-label="Service" class="text-slate-500">{{ $booking->service ?? '—' }}</td>
        <td data-label="Preferred Slot" class="text-slate-500 whitespace-nowrap">
            {{ $booking->preferred_date ? $booking->preferred_date->format('M d, Y') : '—' }}
            @if($booking->preferred_time)<p class="text-xs text-slate-400">{{ $booking->preferred_time }}</p>@endif
        </td>
        <td data-label="Status"><span class="badge {{ $statusBadges[$booking->status] ?? 'badge-gray' }}">{{ ucfirst($booking->status) }}</span></td>
        <td data-label="Received" class="text-slate-500 whitespace-nowrap">{{ $booking->created_at->format('M d, Y') }}</td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.demo-bookings.show', $booking)"
                :deleteUrl="route('admin.demo-bookings.destroy', $booking)"
                tableId="demo-bookings-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No demo bookings found.</td></tr>
@endforelse
