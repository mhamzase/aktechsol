{{-- Drawer form: create / edit blog post. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" enctype="multipart/form-data">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <x-ui.input name="title" label="Title" :value="$blogPost->title" required />
    <x-ui.input name="slug" label="Slug" :value="$blogPost->slug" required />
    <x-ui.select name="category_id" label="Category" :options="$categories->pluck('name', 'id')" :value="$blogPost->category_id"
                 placeholder="Select Category" required />
    <div class="grid grid-cols-2 gap-3">
        <x-ui.date name="published_at" label="Publish Date" :value="$blogPost->published_at" />
        <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$blogPost->sort_order ?? 0" />
    </div>

    <input type="hidden" name="status" value="0">
    <x-ui.toggle name="status" label="Active" :checked="old('status', $blogPost->status ?? true)" />

    <x-ui.textarea name="excerpt" label="Excerpt" :value="$blogPost->excerpt" rows="3" />
    <x-ui.rich-text name="content" label="Content" :value="$blogPost->content" required />

    @if (!empty($featuredUrl))
        <div class="mb-3">
            <p class="ui-label">Current Featured Image</p>
            <img src="{{ $featuredUrl }}" class="h-24 object-cover rounded-lg border border-slate-200" alt="">
        </div>
    @endif
    <x-ui.file name="featured_image" label="Featured Image" accept="image/*" hint="Max 2MB." />

    <x-ui.input name="seo_title" label="SEO Title" :value="$blogPost->seo_title" />
    <x-ui.input name="meta_description" label="Meta Description" :value="$blogPost->meta_description" />
    <x-ui.input name="canonical_url" type="url" label="Canonical URL" :value="$blogPost->canonical_url" />

    <button type="submit" class="btn btn-primary w-full mt-2">{{ $formTitle }}</button>
</form>
