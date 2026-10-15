{{-- Row partial for the FAQs AJAX table. --}}
@forelse ($faqs as $faq)
    <tr>
        <td data-label="Question" class="font-medium text-slate-800 dark:text-slate-100">{{ $faq->question }}</td>
        <td data-label="Sort Order" class="text-slate-500 dark:text-slate-400">{{ $faq->sort_order }}</td>
        <td data-label="Status">
            <button onclick="toggleFaqStatus('{{ route('admin.faqs.toggle-status', $faq) }}', this)"
                    title="Click to {{ $faq->status ? 'deactivate' : 'activate' }}"
                    class="badge {{ $faq->status ? 'badge-green' : 'badge-red' }} hover:opacity-80 transition-opacity cursor-pointer">
                {{ $faq->status ? 'Active' : 'Inactive' }}
            </button>
        </td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.faqs.show', $faq)"
                viewTitle="View Details"
                :editUrl="route('admin.faqs.edit', $faq)"
                editTitle="Edit FAQ"
                :deleteUrl="route('admin.faqs.destroy', $faq)"
                tableId="faqs-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400 text-sm">No FAQs found.</td></tr>
@endforelse
