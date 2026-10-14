{{-- Drawer form: create / edit project. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <x-ui.input name="title" label="Title" :value="$project->title" required />
    <x-ui.input name="slug" label="Slug" :value="$project->slug" required />
    <div class="grid grid-cols-2 gap-3">
        <x-ui.input name="client_name" label="Client Name" :value="$project->client_name" />
        <x-ui.input name="project_url" type="url" label="Project URL" :value="$project->project_url" />
    </div>
    <div class="grid grid-cols-2 gap-3">
        <x-ui.date name="completion_date" label="Completion Date" :value="$project->completion_date" />
        <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$project->sort_order ?? 0" />
    </div>

    <input type="hidden" name="status" value="0">
    <x-ui.toggle name="status" label="Active" :checked="old('status', $project->status ?? true)" />

    <x-ui.textarea name="short_description" label="Short Description" :value="$project->short_description" rows="3" />
    <x-ui.textarea name="full_description" label="Full Description" :value="$project->full_description" rows="5" />

    @if (!empty($featuredUrl))
        <div class="mb-3">
            <p class="ui-label">Current Featured Image</p>
            <img src="{{ $featuredUrl }}" class="h-24 object-cover rounded-lg border border-slate-200" alt="">
        </div>
    @endif
    <x-ui.file name="featured_image" label="Featured Image" accept="image/*" hint="Max 2MB." />
    <x-ui.file name="gallery_images" label="Gallery Images" accept="image/*" :multiple="true"
               hint="You can select multiple images." />

    <x-ui.input name="seo_title" label="SEO Title" :value="$project->seo_title" />
    <x-ui.input name="meta_description" label="Meta Description" :value="$project->meta_description" />
    <x-ui.input name="canonical_url" type="url" label="Canonical URL" :value="$project->canonical_url" />

    <button type="submit" class="btn btn-primary w-full mt-2">{{ $formTitle }}</button>
</form>
