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
            <div class="md:col-span-6">
                <x-ui.select name="assigned_to" label="Assigned To" :value="$project->assigned_to"
                             :options="$users->pluck('name', 'id')->prepend('— Unassigned —', '')->toArray()" />
            </div>
            <div class="md:col-span-6">
                <x-ui.select name="status" label="Status" :value="$project->status ?? 'received'" required
                             :options="['received' => 'Received', 'in_progress' => 'In Progress', 'review' => 'In Review', 'revision' => 'Revision', 'delivered' => 'Delivered']" />
            </div>
            <div class="md:col-span-6">
                <x-ui.input name="progress" type="number" min="0" max="100" label="Progress (%)" :value="$project->progress ?? 0" required />
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
