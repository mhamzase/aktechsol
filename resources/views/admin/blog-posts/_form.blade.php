<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Title <span
                    class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $blogPost->title ?? '') }}" required
                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
            @error('title')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1.5">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $blogPost->slug ?? '') }}"
                placeholder="Auto-generated if empty"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('slug') border-red-500 @enderror">
            <p class="mt-1 text-xs text-gray-400">Leave empty to auto-generate from title.</p>
            @error('slug')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">Category</label>
            <select name="category_id" id="category_id"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Select Category</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('category_id', $blogPost->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-1.5">Published At</label>
            <input type="datetime-local" name="published_at" id="published_at"
                value="{{ old('published_at', optional($blogPost->published_at ?? null)->format('Y-m-d\TH:i')) }}"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
            <input type="number" name="sort_order" id="sort_order"
                value="{{ old('sort_order', $blogPost->sort_order ?? 0) }}"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
            <select name="status" id="status"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="1" {{ old('status', $blogPost->status ?? 1) == 1 ? 'selected' : '' }}>Active
                </option>
                <option value="0" {{ old('status', $blogPost->status ?? 1) == 0 ? 'selected' : '' }}>Inactive
                </option>
            </select>
        </div>
    </div>

    <div>
        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1.5">Excerpt</label>
        <textarea name="excerpt" id="excerpt" rows="2"
            class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('excerpt', $blogPost->excerpt ?? '') }}</textarea>
    </div>
    <div>
        <label for="content" class="block text-sm font-medium text-gray-700 mb-1.5">Content <span
                class="text-red-500">*</span></label>
        <input type="hidden" name="content" id="content-hidden"
            value="{{ old('content', $blogPost->content ?? '') }}">
        <div id="editor-container" style="height: 350px;"></div>
        @error('content')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1.5">Featured Image</label>
        @if (!empty($featuredUrl))
            <div class="mb-2"><img src="{{ $featuredUrl }}" class="h-24 object-cover rounded-lg border"></div>
        @endif
        <input type="file" name="featured_image" id="featured_image" accept="image/*"
            class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        <p class="mt-1.5 text-xs text-gray-400">Recommended 1200×800px. Max 2MB.</p>
        @error('featured_image')
            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-1.5">SEO Title</label>
            <input type="text" name="seo_title" id="seo_title"
                value="{{ old('seo_title', $blogPost->seo_title ?? '') }}"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1.5">Meta
                Description</label>
            <input type="text" name="meta_description" id="meta_description"
                value="{{ old('meta_description', $blogPost->meta_description ?? '') }}"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div class="md:col-span-2">
            <label for="canonical_url" class="block text-sm font-medium text-gray-700 mb-1.5">Canonical URL</label>
            <input type="url" name="canonical_url" id="canonical_url"
                value="{{ old('canonical_url', $blogPost->canonical_url ?? '') }}"
                class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/quill/quill.snow.css') }}">
    <style>
        #editor-container .ql-editor {
            min-height: 300px;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/quill/quill.js') }}"></script>
    <script>
        (function() {
            var hiddenInput = document.getElementById('content-hidden');
            var editorContainer = document.getElementById('editor-container');
            if (!editorContainer || !hiddenInput) return;

            var quill = new Quill(editorContainer, {
                theme: 'snow',
                placeholder: 'Write your post content...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        [{ 'indent': '-1' }, { 'indent': '+1' }],
                        [{ 'align': [] }],
                        ['link', 'image', 'video'],
                        ['blockquote', 'code-block'],
                        [{ 'color': [] }, { 'background': [] }],
                        ['clean']
                    ]
                }
            });

            // Set initial content from hidden input (old value or existing content)
            quill.root.innerHTML = hiddenInput.value;

            // Sync content to hidden input on form submit
            var form = hiddenInput.closest('form');
            if (form) {
                form.addEventListener('submit', function() {
                    hiddenInput.value = quill.root.innerHTML;
                });
            }
        })();
    </script>
@endpush

<script>
    (function() {
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');
        let slugManuallyEdited = false;

        function slugify(text) {
            return text.toString().toLowerCase()
                .trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        slugInput.addEventListener('input', function() {
            slugManuallyEdited = true;
        });

        titleInput.addEventListener('input', function() {
            if (!slugManuallyEdited) {
                slugInput.value = slugify(titleInput.value);
            }
        });

        if (!slugManuallyEdited && !slugInput.value && titleInput.value) {
            slugInput.value = slugify(titleInput.value);
        }
    })();
</script>
