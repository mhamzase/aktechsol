{{-- Row partial for the client projects AJAX table. --}}
@php
    $statusBadges = [
        'received' => 'badge-gray', 'in_progress' => 'badge-blue', 'review' => 'badge-amber',
        'revision' => 'badge-orange', 'delivered' => 'badge-green',
    ];
@endphp
@forelse ($projects as $project)
    <tr>
        <td data-label="Project">
            <p class="font-medium text-slate-800 dark:text-slate-100">{{ $project->title }}</p>
            @if($project->deadline)
            <p class="text-xs text-slate-400">Due {{ $project->deadline->format('M d, Y') }}</p>
            @endif
        </td>
        <td data-label="Client">
            <p class="font-medium text-slate-800 dark:text-slate-100">{{ $project->client_name }}</p>
            <p class="text-xs text-slate-400">{{ $project->client_email }}</p>
        </td>
        <td data-label="Assigned To" class="text-slate-500">{{ $project->assignee?->name ?? '—' }}</td>
        <td data-label="Status"><span class="badge {{ $statusBadges[$project->status] ?? 'badge-gray' }}">{{ $project->statusLabel() }}</span></td>
        <td data-label="Progress" class="whitespace-nowrap">
            <div class="flex items-center gap-2 min-w-[110px]">
                <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full rounded-full" data-progress-bar="{{ $project->progress }}" style="width: {{ $project->progress }}%"></div>
                </div>
                <span class="text-xs font-bold text-slate-500">{{ $project->progress }}%</span>
            </div>
        </td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.client-projects.show', $project)"
                viewTitle="Project Details"
                :editUrl="route('admin.client-projects.edit', $project)"
                editTitle="Edit Project"
                :editWide="true"
                :deleteUrl="route('admin.client-projects.destroy', $project)"
                tableId="projects-table" />
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center py-10 text-slate-400">
            <p class="font-semibold">No projects found.</p>
            <p class="text-sm mt-1">Projects are created automatically when a proposal is accepted.</p>
        </td>
    </tr>
@endforelse
