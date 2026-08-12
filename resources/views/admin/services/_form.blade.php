<div class="bg-white rounded-xl shadow-sm border border-gray-100">

    {{-- Section: Basic Info --}}
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $service->title ?? '') }}" required
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                @error('title')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-red-500">*</span></label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $service->slug ?? '') }}" required
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('slug') border-red-500 @enderror">
                @error('slug')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                <select name="category_id" id="category_id" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('category_id') border-red-500 @enderror">
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $service->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-1.5">Icon (CSS class)</label>
                <input type="text" name="icon" id="icon" value="{{ old('icon', $service->icon ?? '') }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $service->sort_order ?? 0) }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" id="status"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="1" {{ old('status', $service->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $service->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Section: Descriptions --}}
    <div class="p-6 border-b border-gray-100">
        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">Descriptions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1.5">Short Description</label>
                <textarea name="short_description" id="short_description" rows="4"
                          class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('short_description', $service->short_description ?? '') }}</textarea>
            </div>
            <div>
                <label for="full_description" class="block text-sm font-medium text-gray-700 mb-1.5">Full Description</label>
                <textarea name="full_description" id="full_description" rows="4"
                          class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('full_description', $service->full_description ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Section: Image + SEO --}}
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">Service Image</h3>

                {{-- Current saved thumbnail --}}
                @if(!empty($thumbnailUrl))
                    <div class="mb-3" id="current-thumbnail">
                        <p class="text-xs text-gray-500 mb-1">Current Image</p>
                        <img src="{{ $thumbnailUrl }}" class="h-24 object-cover rounded-lg border border-gray-200">
                    </div>
                @endif

                {{-- Live preview (hidden by default) --}}
                <div id="image-preview-container" class="mb-3 hidden">
                    <p class="text-xs text-gray-500 mb-1">New Image Preview</p>
                    <img id="image-preview" src="" class="h-24 object-cover rounded-lg border border-gray-200">
                </div>

                <label for="image" class="block text-sm font-medium text-gray-700 mb-1.5">Upload Image</label>
                <input type="file" name="image" id="image" accept="image/*" onchange="previewImage(event)"
                       class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="mt-1.5 text-xs text-gray-400">Recommended 800×600px. Max 2MB.</p>
                @error('image')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">SEO</h3>
                <div class="space-y-4">
                    <div>
                        <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-1.5">SEO Title</label>
                        <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $service->seo_title ?? '') }}"
                               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1.5">Meta Description</label>
                        <input type="text" name="meta_description" id="meta_description" value="{{ old('meta_description', $service->meta_description ?? '') }}"
                               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="canonical_url" class="block text-sm font-medium text-gray-700 mb-1.5">Canonical URL</label>
                        <input type="url" name="canonical_url" id="canonical_url" value="{{ old('canonical_url', $service->canonical_url ?? '') }}"
                               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image-preview');
        const container = document.getElementById('image-preview-container');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
                // Optionally hide the current thumbnail when a new image is selected
                const current = document.getElementById('current-thumbnail');
                if (current) current.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            container.classList.add('hidden');
            preview.src = '';
            // Show current thumbnail again if it existed
            const current = document.getElementById('current-thumbnail');
            if (current) current.classList.remove('hidden');
        }
    }
</script>
@endpush
