{{-- Drawer form: create / edit service. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <x-ui.input name="title" label="Title" :value="$service->title" required />
    <x-ui.input name="slug" label="Slug" :value="$service->slug" required
                hint="URL-friendly identifier, e.g. web-development." />
    <x-ui.select name="category_id" label="Category" :options="$categories->pluck('name', 'id')" :value="$service->category_id"
                 placeholder="Select Category" required />
    <div class="grid grid-cols-2 gap-3">
        <x-ui.input name="icon" label="Icon (CSS class)" :value="$service->icon" />
        <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$service->sort_order ?? 0" />
    </div>

    <input type="hidden" name="status" value="0">
    <x-ui.toggle name="status" label="Active" :checked="old('status', $service->status ?? true)" />

    <x-ui.textarea name="short_description" label="Short Description" :value="$service->short_description" rows="3" />
    <x-ui.textarea name="full_description" label="Full Description" :value="$service->full_description" rows="5" />

    @if (!empty($thumbnailUrl))
        <div class="mb-3">
            <p class="ui-label">Current Image</p>
            <img src="{{ $thumbnailUrl }}" class="h-24 object-cover rounded-lg border border-slate-200" alt="">
        </div>
    @endif
    <x-ui.file name="image" label="Service Image" accept="image/*" hint="Recommended 800×600px. Max 2MB." />

    <x-ui.input name="seo_title" label="SEO Title" :value="$service->seo_title" />
    <x-ui.input name="meta_description" label="Meta Description" :value="$service->meta_description" />
    <x-ui.input name="canonical_url" type="url" label="Canonical URL" :value="$service->canonical_url" />

    <button type="submit" class="btn btn-primary w-full mt-2">{{ $formTitle }}</button>
</form>
