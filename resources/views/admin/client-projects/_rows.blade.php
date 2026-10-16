@forelse($projects as $project)
<tr class="border-b border-slate-50 dark:border-slate-700/50 hover:bg-slate-50/60 dark:hover:bg-slate-700/30 transition">
    <td class="px-6 py-4" data-label="Project">
        <p class="font-bold text-slate-800 dark:text-slate-100">{{ $project->title }}</p>
        @if($project->deadline)
        <p class="text-xs text-slate-400 mt-0.5">Due {{ $project->deadline->format('M d, Y') }}</p>
        @endif
    </td>
    <td class="px-6 py-4" data-label="Client">
        <p class="font-semibold text-slate-700 dark:text-slate-200">{{ $project->client_name }}</p>
        <p class="text-xs text-slate-400">{{ $project->client_email }}</p>
    </td>
    <td class="px-6 py-4" data-label="Assigned To">
        <p class="text-slate-600 dark:text-slate-300">{{ $project->assignee?->name ?? '—' }}</p>
    </td>
    <td class="px-6 py-4" data-label="Status">
        @php
            $colors = [
                'received' => 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300',
                'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300',
                'review' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                'revision' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/40 dark:text-orange-300',
                'delivered' => 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300',
            ];
        @endphp
        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $colors[$project->status] ?? $colors['received'] }}">
            {{ $project->statusLabel() }}
        </span>
    </td>
    <td class="px-6 py-4" data-label="Progress">
        <div class="flex items-center gap-2 min-w-[120px]">
            <div class="flex-1 h-2 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 rounded-full transition-all" style="width: {{ $project->progress }}%"></div>
            </div>
            <span class="text-xs font-bold text-slate-500 w-9 text-right">{{ $project->progress }}%</span>
        </div>
    </td>
    <td class="px-6 py-4 text-right" data-label="Actions">
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
    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
        <p class="font-semibold">No projects found.</p>
        <p class="text-sm mt-1">Projects are created automatically when a proposal is accepted.</p>
    </td>
</tr>
@endforelse
