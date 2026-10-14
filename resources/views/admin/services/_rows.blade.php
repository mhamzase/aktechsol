{{-- Row partial for the services AJAX table. Every <td> carries data-label for the mobile card view. --}}
@forelse ($services as $service)
    <tr>
        <td data-label="Image">
            @if ($service->getThumbnailUrl())
                <img src="{{ $service->getThumbnailUrl() }}" alt="" class="h-10 w-16 object-cover rounded-lg">
            @else
                <span class="inline-flex h-10 w-16 bg-slate-100 dark:bg-slate-700 rounded-lg items-center justify-center text-slate-400 dark:text-slate-500 text-xs">No img</span>
            @endif
        </td>
        <td data-label="Title" class="font-medium text-slate-800 dark:text-slate-100">{{ $service->title }}</td>
        <td data-label="Category" class="text-slate-500 dark:text-slate-400">{{ $service->category?->name ?? '—' }}</td>
        <td data-label="Sort Order" class="text-slate-500 dark:text-slate-400">{{ $service->sort_order }}</td>
        <td data-label="Status">
            <button onclick="toggleServiceStatus('{{ route('admin.services.toggle-status', $service) }}', this)"
                    title="Click to {{ $service->status ? 'deactivate' : 'activate' }}"
                    class="badge {{ $service->status ? 'badge-green' : 'badge-red' }} hover:opacity-80 transition-opacity cursor-pointer">
                {{ $service->status ? 'Active' : 'Inactive' }}
            </button>
        </td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.services.show', $service)"
                viewTitle="View Details"
                :editUrl="route('admin.services.edit', $service)"
                editTitle="Edit Service"
                :deleteUrl="route('admin.services.destroy', $service)"
                tableId="services-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No services found.</td></tr>
@endforelse
