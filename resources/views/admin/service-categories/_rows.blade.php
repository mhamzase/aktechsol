{{-- Row partial for the service-categories AJAX table. --}}
@forelse ($categories as $category)
    <tr>
        <td data-label="Name" class="font-medium text-slate-800 dark:text-slate-100">{{ $category->name }}</td>
        <td data-label="Slug" class="text-slate-500 dark:text-slate-400">{{ $category->slug }}</td>
        <td data-label="Sort Order" class="text-slate-500 dark:text-slate-400">{{ $category->sort_order }}</td>
        <td data-label="Status">
            <button onclick="toggleCategoryStatus('{{ route('admin.service-categories.toggle-status', $category) }}', this)"
                    title="Click to {{ $category->status ? 'deactivate' : 'activate' }}"
                    class="badge {{ $category->status ? 'badge-green' : 'badge-red' }} hover:opacity-80 transition-opacity cursor-pointer">
                {{ $category->status ? 'Active' : 'Inactive' }}
            </button>
        </td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.service-categories.show', $category)"
                viewTitle="View Details"
                :editUrl="route('admin.service-categories.edit', $category)"
                editTitle="Edit Category"
                :deleteUrl="route('admin.service-categories.destroy', $category)"
                tableId="service-categories-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="5" class="text-center py-10 text-slate-400 text-sm">No categories found.</td></tr>
@endforelse
