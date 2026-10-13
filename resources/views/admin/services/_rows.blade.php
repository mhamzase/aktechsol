{{-- Row partial for the services AJAX table. Every <td> carries data-label for the mobile card view. --}}
@forelse ($services as $service)
    <tr>
        <td data-label="Image">
            @if ($service->getThumbnailUrl())
                <img src="{{ $service->getThumbnailUrl() }}" alt="" class="h-10 w-16 object-cover rounded">
            @else
                <span class="inline-flex h-10 w-16 bg-slate-100 rounded items-center justify-center text-slate-400 text-xs">No img</span>
            @endif
        </td>
        <td data-label="Title" class="font-medium text-slate-800">{{ $service->title }}</td>
        <td data-label="Category" class="text-slate-500">{{ $service->category?->name ?? '—' }}</td>
        <td data-label="Sort Order" class="text-slate-500">{{ $service->sort_order }}</td>
        <td data-label="Status">
            <span class="badge {{ $service->status ? 'badge-green' : 'badge-red' }}">{{ $service->status ? 'Active' : 'Inactive' }}</span>
        </td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <button type="button" onclick="openDrawer('{{ route('admin.services.edit', $service) }}', 'Edit Service')" class="btn btn-secondary !py-1.5 !px-3 !text-xs">Edit</button>
            <button class="btn btn-danger !py-1.5 !px-3 !text-xs"
                    onclick="confirmDelete('{{ route('admin.services.destroy', $service) }}', 'services-table', 'Delete this service?')">Delete</button>
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No services found.</td></tr>
@endforelse
