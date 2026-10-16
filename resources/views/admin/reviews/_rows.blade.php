{{-- Row partial for the reviews AJAX table. --}}
@php
    $statusBadges = [
        'pending' => 'badge-amber', 'approved' => 'badge-green', 'rejected' => 'badge-red',
    ];
@endphp
@forelse ($reviews as $review)
    <tr>
        <td data-label="Reviewer">
            <p class="font-medium text-slate-800 dark:text-slate-100">{{ $review->reviewer_name }}</p>
            <p class="text-xs text-slate-400">{{ $review->created_at->format('M d, Y') }}</p>
        </td>
        <td data-label="Project">
            <p class="font-medium text-slate-800 dark:text-slate-100">{{ $review->project->title ?? '—' }}</p>
            @if($review->project)
            <p class="text-xs text-slate-400">{{ $review->project->client_name }}</p>
            @endif
        </td>
        <td data-label="Rating" class="whitespace-nowrap">
            <span class="inline-flex items-center gap-0.5">{!! $review->starsHtml() !!}</span>
        </td>
        <td data-label="Review">
            <p class="text-sm text-slate-600 dark:text-slate-300 max-w-xs truncate">{{ $review->review ?: '—' }}</p>
        </td>
        <td data-label="Status"><span class="badge {{ $statusBadges[$review->status] ?? 'badge-gray' }}">{{ $review->statusLabel() }}</span></td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <div class="flex items-center gap-1">
                @if($review->status === 'pending')
                <button type="button" data-approve-review data-url="{{ route('admin.reviews.approve', $review) }}" title="Approve"
                        class="p-1.5 rounded-lg text-green-600 hover:bg-green-50 dark:hover:bg-green-900/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </button>
                <button type="button" data-reject-review data-url="{{ route('admin.reviews.reject', $review) }}" title="Reject"
                        class="p-1.5 rounded-lg text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @endif
                <x-admin.row-actions
                    :viewUrl="route('admin.reviews.show', $review)"
                    viewTitle="Review Details"
                    :deleteUrl="route('admin.reviews.destroy', $review)"
                    tableId="reviews-table" />
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-10 text-slate-400">
            <p class="font-semibold">No reviews found.</p>
            <p class="text-sm mt-1">Reviews appear here after clients submit them.</p>
        </td>
    </tr>
@endforelse
