<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $blogCategory->name ?? '') }}" required
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
            @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1.5">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $blogCategory->slug ?? '') }}"
                   placeholder="Auto-generated if empty"
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('slug') border-red-500 @enderror">
            <p class="mt-1 text-xs text-gray-400">Leave empty to auto-generate from name.</p>
            @error('slug')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $blogCategory->sort_order ?? 0) }}"
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
            <select name="status" id="status"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="1" {{ old('status', $blogCategory->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $blogCategory->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
</div>

<script>
    (function() {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        function slugify(text) {
            return text.toString().toLowerCase()
                .trim()
                .replace(/\s+/g, '-')
                .replace(/[^\w\-]+/g, '')
                .replace(/\-\-+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }

        // Auto-generate slug when name changes, but only if slug field was not manually edited
        let slugManuallyEdited = false;

        slugInput.addEventListener('input', function() {
            slugManuallyEdited = true;
        });

        nameInput.addEventListener('input', function() {
            if (!slugManuallyEdited) {
                slugInput.value = slugify(nameInput.value);
            }
        });

        // On page load, if slug is empty and name exists (edit page), populate if not manually edited
        if (!slugManuallyEdited && !slugInput.value && nameInput.value) {
            slugInput.value = slugify(nameInput.value);
        }
    })();
</script>
