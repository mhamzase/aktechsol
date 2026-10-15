{{-- Drawer form: create / edit blog post. AJAX submit (data-ajax-form), no page refresh. --}}
<form data-ajax-form data-reload-table="{{ $tableId }}" method="POST" action="{{ $action }}" enctype="multipart/form-data" novalidate>
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    {{-- Content --}}
    <div class="mb-5">
        <h3 class="section-heading">
            <span class="sec-icon sec-blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </span>
            Content
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="sm:col-span-2"><x-ui.input name="title" label="Title" :value="$blogPost->title" required placeholder="My Awesome Post" /></div>
            <x-ui.input name="slug" label="Slug" :value="$blogPost->slug" required placeholder="my-awesome-post" hint="Auto-generated from title." />
            <x-ui.select name="category_id" label="Category" :options="$categories->pluck('name', 'id')" :value="$blogPost->category_id" placeholder="Select Category" required />
            <x-ui.date name="published_at" label="Publish Date" :value="$blogPost->published_at" />
            <x-ui.input name="sort_order" type="number" label="Sort Order" :value="$blogPost->sort_order ?? 0" />
            <div class="sm:col-span-2">
                <input type="hidden" name="status" value="0">
                <x-ui.toggle name="status" label="Active" :checked="old('status', $blogPost->status ?? true)" hint="Show this post on the website." />
            </div>
            <div class="sm:col-span-2"><x-ui.textarea name="excerpt" label="Excerpt" :value="$blogPost->excerpt" rows="2" placeholder="Short summary…" /></div>
            <div class="sm:col-span-2"><x-ui.rich-text name="content" label="Content" :value="$blogPost->content" required /></div>
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
        <x-ui.file name="featured_image" label="Featured Image" accept="image/*" hint="Recommended 1200×630px. Max 2MB." />
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
            <x-ui.input name="seo_title" label="SEO Title" :value="$blogPost->seo_title" placeholder="Custom title for search engines" />
            <x-ui.textarea name="meta_description" label="Meta Description" :value="$blogPost->meta_description" rows="2" />
            <x-ui.input name="canonical_url" type="url" label="Canonical URL" :value="$blogPost->canonical_url" placeholder="https://…" />
        </div>
    </div>

    <x-admin.drawer-footer :save-label="$formTitle" />
</form>
