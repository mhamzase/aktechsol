<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 space-y-5">
    <div>
        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $project->title ?? '') }}" required
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                @error('title')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-red-500">*</span></label>
                <input type="text" name="slug" id="slug" value="{{ old('slug', $project->slug ?? '') }}" required
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('slug') border-red-500 @enderror">
                @error('slug')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1.5">Client Name</label>
                <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $project->client_name ?? '') }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="project_url" class="block text-sm font-medium text-gray-700 mb-1.5">Project URL</label>
                <input type="url" name="project_url" id="project_url" value="{{ old('project_url', $project->project_url ?? '') }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="completion_date" class="block text-sm font-medium text-gray-700 mb-1.5">Completion Date</label>
                <input type="date" name="completion_date" id="completion_date" value="{{ old('completion_date', optional($project->completion_date ?? null)->format('Y-m-d')) }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $project->sort_order ?? 0) }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" id="status"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="1" {{ old('status', $project->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $project->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>
    </div>

    <div>
        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1.5">Short Description</label>
        <textarea name="short_description" id="short_description" rows="3"
                  class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('short_description', $project->short_description ?? '') }}</textarea>
    </div>
    <div>
        <label for="full_description" class="block text-sm font-medium text-gray-700 mb-1.5">Full Description</label>
        <textarea name="full_description" id="full_description" rows="6"
                  class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('full_description', $project->full_description ?? '') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-1.5">Featured Image</label>
            @if(!empty($featuredUrl))
                <div class="mb-2"><img src="{{ $featuredUrl }}" class="h-24 object-cover rounded-lg border"></div>
            @endif
            <input type="file" name="featured_image" id="featured_image" accept="image/*"
                   class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="mt-1.5 text-xs text-gray-400">Recommended 1200×800px. Max 2MB.</p>
            @error('featured_image')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-1.5">Gallery Images</label>
            @if(!empty($galleryImages) && count($galleryImages))
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach($galleryImages as $img)
                        <img src="{{ $img->getUrl() }}" class="h-16 w-16 object-cover rounded-lg border">
                    @endforeach
                </div>
            @endif
            <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple
                   class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="mt-1.5 text-xs text-gray-400">Multiple images allowed. Each max 2MB.</p>
            @error('gallery_images.*')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wide mb-4">SEO</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="seo_title" class="block text-sm font-medium text-gray-700 mb-1.5">SEO Title</label>
                <input type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $project->seo_title ?? '') }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1.5">Meta Description</label>
                <input type="text" name="meta_description" id="meta_description" value="{{ old('meta_description', $project->meta_description ?? '') }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="md:col-span-2">
                <label for="canonical_url" class="block text-sm font-medium text-gray-700 mb-1.5">Canonical URL</label>
                <input type="url" name="canonical_url" id="canonical_url" value="{{ old('canonical_url', $project->canonical_url ?? '') }}"
                       class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>
    </div>
</div>
