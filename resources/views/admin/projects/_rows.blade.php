{{-- Row partial for the projects AJAX table. --}}
@forelse ($projects as $project)
    <tr>
        <td data-label="Image">
            @if ($project->getFeaturedImageUrl())
                <img src="{{ $project->getFeaturedImageUrl() }}?v={{ $project->updated_at->timestamp }}" alt="" class="h-10 w-16 object-cover rounded-lg">
            @else
                <span class="inline-flex h-10 w-16 bg-slate-100 dark:bg-slate-700 rounded-lg items-center justify-center text-slate-400 dark:text-slate-500 text-xs">No img</span>
            @endif
        </td>
        <td data-label="Title" class="font-medium text-slate-800 dark:text-slate-100">{{ $project->title }}</td>
        <td data-label="Client" class="text-slate-500 dark:text-slate-400">{{ $project->client_name ?? '—' }}</td>
        <td data-label="Sort Order" class="text-slate-500 dark:text-slate-400">{{ $project->sort_order }}</td>
        <td data-label="Status">
            <button onclick="toggleProjectStatus('{{ route('admin.projects.toggle-status', $project) }}', this)"
                    title="Click to {{ $project->status ? 'deactivate' : 'activate' }}"
                    class="badge {{ $project->status ? 'badge-green' : 'badge-red' }} hover:opacity-80 transition-opacity cursor-pointer">
                {{ $project->status ? 'Active' : 'Inactive' }}
            </button>
        </td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.projects.show', $project)"
                viewTitle="View Details"
                :editUrl="route('admin.projects.edit', $project)"
                editTitle="Edit Project"
                :deleteUrl="route('admin.projects.destroy', $project)"
                tableId="projects-table" />
        </td>
    </tr>
@empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400 text-sm">No projects found.</td></tr>
@endforelse
