{{-- Row partial for the newsletter-subscribers AJAX table. --}}
@forelse ($subscribers as $subscriber)
    <tr>
        <td data-label="Email" class="font-medium text-slate-800">{{ $subscriber->email }}</td>
        <td data-label="Status"><span class="badge {{ $subscriber->is_subscribed ? 'badge-green' : 'badge-gray' }}">{{ $subscriber->is_subscribed ? 'Subscribed' : 'Unsubscribed' }}</span></td>
        <td data-label="Subscribed At" class="text-slate-500 whitespace-nowrap">{{ $subscriber->created_at->format('M d, Y') }}</td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <button class="btn btn-danger !py-1.5 !px-3 !text-xs" onclick="confirmDelete('{{ route('admin.newsletter-subscribers.destroy', $subscriber) }}', 'newsletter-table', 'Delete this subscriber?')">Delete</button>
        </td>
    </tr>
@empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400 text-sm">No subscribers found.</td></tr>
@endforelse
