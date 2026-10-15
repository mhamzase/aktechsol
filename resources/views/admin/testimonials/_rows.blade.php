{{-- Row partial for the testimonials AJAX table. --}}
@forelse ($testimonials as $testimonial)
    <tr>
        <td data-label="Photo">
            @if ($testimonial->getPhotoUrl())
                <img src="{{ $testimonial->getPhotoUrl() }}?v={{ $testimonial->updated_at->timestamp }}" alt="" class="h-10 w-10 rounded-full object-cover">
            @else
                <span class="inline-flex h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-500/20 text-[#1d4ed8] dark:text-blue-400 font-bold text-xs items-center justify-center">{{ strtoupper(substr($testimonial->client_name, 0, 1)) }}</span>
            @endif
        </td>
        <td data-label="Client" class="font-medium text-slate-800 dark:text-slate-100">{{ $testimonial->client_name }}</td>
        <td data-label="Company" class="text-slate-500 dark:text-slate-400">{{ $testimonial->company ?? '—' }}</td>
        <td data-label="Rating" class="text-amber-400 text-sm whitespace-nowrap">{{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}</td>
        <td data-label="Status">
            <button onclick="toggleTestimonialStatus('{{ route('admin.testimonials.toggle-status', $testimonial) }}', this)"
                    title="Click to {{ $testimonial->status ? 'deactivate' : 'activate' }}"
                    class="badge {{ $testimonial->status ? 'badge-green' : 'badge-red' }} hover:opacity-80 transition-opacity cursor-pointer">
                {{ $testimonial->status ? 'Active' : 'Inactive' }}
            </button>
        </td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.testimonials.show', $testimonial)"
                viewTitle="View Details"
                :editUrl="route('admin.testimonials.edit', $testimonial)"
                editTitle="Edit Testimonial"
                :deleteUrl="route('admin.testimonials.destroy', $testimonial)"
                tableId="testimonials-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No testimonials found.</td></tr>
@endforelse
