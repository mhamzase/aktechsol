{{-- Drawer form: create / edit project. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    {{-- Basic Info --}}
    <div class="mb-5">
        <h3 class="section-heading">
            <span class="sec-icon sec-blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            Basic Info
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="sm:col-span-2"><x-ui.input name="title" label="Title" :value="$project->title" required placeholder="E-commerce Website" /></div>
            <x-ui.input name="slug" label="Slug" :value="$project->slug" required placeholder="ecommerce-website" hint="Auto-generated from title." />
            <x-ui.input name="client_name" label="Client Name" :value="$project->client_name" placeholder="Acme Inc." />
            <x-ui.input name="project_url" type="url" label="Project URL" :value="$project->project_url" placeholder="https://…" />
            <x-ui.date name="completion_date" label="Completion Date" :value="$project->completion_date" />
            <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$project->sort_order ?? 0" />
            <div class="sm:col-span-2">
                <input type="hidden" name="status" value="0">
                <x-ui.toggle name="status" label="Active" :checked="old('status', $project->status ?? true)" hint="Show in portfolio." />
            </div>
            <div class="sm:col-span-2"><x-ui.textarea name="short_description" label="Short Description" :value="$project->short_description" rows="2" placeholder="One-line summary…" /></div>
            <div class="sm:col-span-2"><x-ui.rich-text name="full_description" label="Full Description" :value="$project->full_description" /></div>
        </div>
    </div>

    {{-- Media --}}
    <div class="mb-5">
        <h3 class="section-heading">
            <span class="sec-icon sec-violet">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
            Media
        </h3>
        @if (!empty($featuredUrl))
            <div class="mb-3">
                <p class="ui-label">Current Featured Image</p>
                <img src="{{ $featuredUrl }}" class="h-24 object-cover rounded-lg border border-slate-200 dark:border-slate-700" alt="">
            </div>
        @endif
        <x-ui.file name="featured_image" label="Featured Image" accept="image/*" hint="Recommended 1200×800px. Max 2MB." />
        <x-ui.file name="gallery_images" label="Gallery Images" accept="image/*" :multiple="true" hint="You can select multiple images." />
    </div>

    {{-- SEO --}}
    <div class="mb-2">
        <h3 class="section-heading">
            <span class="sec-icon sec-green">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </span>
            SEO
        </h3>
        <div class="grid grid-cols-1 gap-3">
            <x-ui.input name="seo_title" label="SEO Title" :value="$project->seo_title" placeholder="Custom title for search engines" />
            <x-ui.textarea name="meta_description" label="Meta Description" :value="$project->meta_description" rows="2" />
            <x-ui.input name="canonical_url" type="url" label="Canonical URL" :value="$project->canonical_url" placeholder="https://…" />
        </div>
    </div>

    <x-admin.drawer-footer :save-label="$formTitle" />
</form>
