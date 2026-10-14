{{-- Row partial for the testimonials AJAX table. --}}
@forelse ($testimonials as $testimonial)
    <tr>
        <td data-label="Photo">
            @if ($testimonial->getPhotoUrl())
                <img src="{{ $testimonial->getPhotoUrl() }}" alt="" class="h-10 w-10 rounded-full object-cover">
            @else
                <span class="inline-flex h-10 w-10 rounded-full bg-blue-100 text-[#1d4ed8] font-bold text-xs items-center justify-center">{{ strtoupper(substr($testimonial->client_name, 0, 1)) }}</span>
            @endif
        </td>
        <td data-label="Client" class="font-medium text-slate-800">{{ $testimonial->client_name }}</td>
        <td data-label="Company" class="text-slate-500">{{ $testimonial->company ?? '—' }}</td>
        <td data-label="Rating" class="text-amber-400 text-sm whitespace-nowrap">{{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}</td>
        <td data-label="Status"><span class="badge {{ $testimonial->status ? 'badge-green' : 'badge-red' }}">{{ $testimonial->status ? 'Active' : 'Inactive' }}</span></td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <button type="button" onclick="openDrawer('{{ route('admin.testimonials.edit', $testimonial) }}', 'Edit Testimonial')" class="btn btn-secondary !py-1.5 !px-3 !text-xs">Edit</button>
            <button class="btn btn-danger !py-1.5 !px-3 !text-xs" onclick="confirmDelete('{{ route('admin.testimonials.destroy', $testimonial) }}', 'testimonials-table', 'Delete this testimonial?')">Delete</button>
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No testimonials found.</td></tr>
@endforelse
