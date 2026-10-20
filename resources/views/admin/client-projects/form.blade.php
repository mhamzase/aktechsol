{{-- Client project create/edit drawer form --}}
<div class="drawer-content">
    <form data-ajax-form data-reload-table="projects-table" method="POST"
          action="{{ $project->exists ? route('admin.client-projects.update', $project) : route('admin.client-projects.store') }}"
          class="px-6 pb-6 pt-2 space-y-4">
        @csrf
        @if($project->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-12">
                <x-ui.input name="title" label="Project Title" :value="$project->title" required placeholder="Website Development" />
            </div>
            <div class="md:col-span-6">
                <x-ui.input name="client_name" label="Client Name" :value="$project->client_name" required placeholder="John Doe" />
            </div>
            <div class="md:col-span-6">
                <x-ui.input name="client_email" type="email" label="Client Email" :value="$project->client_email" required placeholder="john@example.com" />
            </div>
            <div class="md:col-span-12">
                <x-ui.multi-select name="team[]" label="Assign Team"
                                   :options="$users->pluck('name', 'id')->toArray()"
                                   :selected="$project->exists ? $project->team->pluck('id')->all() : []"
                                   placeholder="Select team members…"
                                   hint="Assign one or more team members to this project." />
            </div>
            <div class="md:col-span-6">
                <x-ui.select name="status" label="Status" :value="$project->status ?? 'received'" required
                             :options="['received' => 'Received', 'in_progress' => 'In Progress', 'review' => 'In Review', 'revision' => 'Revision', 'delivered' => 'Delivered']" />
            </div>
            <div class="md:col-span-6">
                <span class="ui-label">Progress (%)<span class="ui-required">*</span></span>
                <div class="flex items-center gap-3">
                    <input type="range" name="progress" min="0" max="100" step="5" value="{{ $project->progress ?? 0 }}"
                           data-progress-slider
                           class="flex-1 h-2 bg-slate-200 dark:bg-slate-700 rounded-full appearance-none cursor-pointer accent-blue-600">
                    <span data-progress-value class="text-sm font-extrabold text-blue-600 w-12 text-right">{{ $project->progress ?? 0 }}%</span>
                </div>
                <p class="ui-hint">Drag to set completion.</p>
            </div>
            <div class="md:col-span-6">
                <div class="grid grid-cols-2 gap-3">
                    <x-ui.date name="started_at" label="Start Date" :value="$project->started_at?->format('Y-m-d')" />
                    <x-ui.date name="deadline" label="Deadline" :value="$project->deadline?->format('Y-m-d')" />
                </div>
            </div>
        </div>

        <x-admin.drawer-footer :save-label="$project->exists ? 'Update Project' : 'Create Project'" />
    </form>
</div>
