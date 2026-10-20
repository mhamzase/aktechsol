{{-- Drawer form: create / edit job listing. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" novalidate>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    {{-- Listing details --}}
    <div class="mb-5">
        <h3 class="section-heading">
            <span class="sec-icon sec-blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
            Listing Details
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="sm:col-span-2"><x-ui.input name="title" label="Job Title" :value="$job->title" required placeholder="Video Editor (Remote)" /></div>
            <x-ui.select name="type" label="Type" :options="['job' => 'Job', 'internship' => 'Internship']" :value="$job->type" required />
            <x-ui.select name="location_type" label="Location" :options="['remote' => 'Remote', 'onsite' => 'On-site', 'hybrid' => 'Hybrid']" :value="$job->location_type" required />
            <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$job->sort_order ?? 0" />
            <div>
                <input type="hidden" name="is_open" value="0">
                <x-ui.toggle name="is_open" label="Open for Applications" :checked="old('is_open', $job->is_open ?? true)" hint="Closed jobs hide from the careers page." />
            </div>
            <div class="sm:col-span-2"><x-ui.textarea name="description" label="Description" :value="$job->description" rows="6" required placeholder="What the role is about, responsibilities, what we offer…" /></div>
            <div class="sm:col-span-2"><x-ui.textarea name="requirements" label="Requirements" :value="$job->requirements" rows="5" placeholder="Skills and experience the candidate must have…" hint="Shown as its own section on the job page." /></div>
        </div>
    </div>

    <x-admin.drawer-footer :save-label="$formTitle" />
</form>
