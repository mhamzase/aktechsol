{{-- Row partial for the contact-messages AJAX table. --}}
@php
    $statusBadges = ['new' => 'badge-amber', 'read' => 'badge-blue', 'replied' => 'badge-green'];
@endphp
@forelse ($messages as $message)
    <tr>
        <td data-label="Name" class="font-medium text-slate-800">{{ $message->name }}</td>
        <td data-label="Email" class="text-slate-500">{{ $message->email }}</td>
        <td data-label="Subject" class="text-slate-500">{{ $message->subject }}</td>
        <td data-label="Status"><span class="badge {{ $statusBadges[$message->status] ?? 'badge-gray' }}">{{ ucfirst($message->status) }}</span></td>
        <td data-label="Received" class="text-slate-500 whitespace-nowrap">{{ $message->created_at->format('M d, Y') }}</td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <a href="{{ route('admin.contact-messages.show', $message) }}" class="btn btn-secondary !py-1.5 !px-3 !text-xs">View</a>
            <button class="btn btn-danger !py-1.5 !px-3 !text-xs" onclick="confirmDelete('{{ route('admin.contact-messages.destroy', $message) }}', 'contact-messages-table', 'Delete this message?')">Delete</button>
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No messages found.</td></tr>
@endforelse
