{{-- Row partial for the projects AJAX table. --}}
@forelse ($projects as $project)
    <tr>
        <td data-label="Image">
            @if ($project->getFeaturedImageUrl())
                <img src="{{ $project->getFeaturedImageUrl() }}" alt="" class="h-10 w-16 object-cover rounded">
            @else
                <span class="inline-flex h-10 w-16 bg-slate-100 rounded items-center justify-center text-slate-400 text-xs">No img</span>
            @endif
        </td>
        <td data-label="Title" class="font-medium text-slate-800">{{ $project->title }}</td>
        <td data-label="Client" class="text-slate-500">{{ $project->client_name ?? '—' }}</td>
        <td data-label="Sort Order" class="text-slate-500">{{ $project->sort_order }}</td>
        <td data-label="Status"><span class="badge {{ $project->status ? 'badge-green' : 'badge-red' }}">{{ $project->status ? 'Active' : 'Inactive' }}</span></td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-secondary !py-1.5 !px-3 !text-xs">Edit</a>
            <button class="btn btn-danger !py-1.5 !px-3 !text-xs" onclick="confirmDelete('{{ route('admin.projects.destroy', $project) }}', 'projects-table', 'Delete this project?')">Delete</button>
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No projects found.</td></tr>
@endforelse
