{{-- Row partial for the FAQs AJAX table. --}}
@forelse ($faqs as $faq)
    <tr>
        <td data-label="Question" class="font-medium text-slate-800">{{ $faq->question }}</td>
        <td data-label="Sort Order" class="text-slate-500">{{ $faq->sort_order }}</td>
        <td data-label="Status"><span class="badge {{ $faq->status ? 'badge-green' : 'badge-red' }}">{{ $faq->status ? 'Active' : 'Inactive' }}</span></td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <button type="button" onclick="openDrawer('{{ route('admin.faqs.edit', $faq) }}', 'Edit FAQ')" class="btn btn-secondary !py-1.5 !px-3 !text-xs">Edit</button>
            <button class="btn btn-danger !py-1.5 !px-3 !text-xs" onclick="confirmDelete('{{ route('admin.faqs.destroy', $faq) }}', 'faqs-table', 'Delete this FAQ?')">Delete</button>
        </td>
    </tr>
@empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400 text-sm">No FAQs found.</td></tr>
@endforelse
