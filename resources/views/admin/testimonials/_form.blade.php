<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
            <label for="client_name" class="block text-sm font-medium text-gray-700 mb-1.5">Client Name <span class="text-red-500">*</span></label>
            <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $testimonial->client_name ?? '') }}" required
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('client_name') border-red-500 @enderror">
            @error('client_name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="client_position" class="block text-sm font-medium text-gray-700 mb-1.5">Position</label>
            <input type="text" name="client_position" id="client_position" value="{{ old('client_position', $testimonial->client_position ?? '') }}"
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="company" class="block text-sm font-medium text-gray-700 mb-1.5">Company</label>
            <input type="text" name="company" id="company" value="{{ old('company', $testimonial->company ?? '') }}"
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="rating" class="block text-sm font-medium text-gray-700 mb-1.5">Rating (1-5)</label>
            <input type="number" name="rating" id="rating" min="1" max="5" value="{{ old('rating', $testimonial->rating ?? 5) }}"
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 10) }}"
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
            <select name="status" id="status"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="1" {{ old('status', $testimonial->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $testimonial->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>

    <div>
        <label for="content" class="block text-sm font-medium text-gray-700 mb-1.5">Testimonial Content <span class="text-red-500">*</span></label>
        <textarea name="content" id="content" rows="4" required
                  class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-500 @enderror">{{ old('content', $testimonial->content ?? '') }}</textarea>
        @error('content')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="photo" class="block text-sm font-medium text-gray-700 mb-1.5">Client Photo</label>
        @if(!empty($photoUrl))
            <div class="mb-2"><img src="{{ $photoUrl }}" class="h-16 w-16 rounded-full object-cover"></div>
        @endif
        <input type="file" name="photo" id="photo" accept="image/*"
               class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        <p class="mt-1.5 text-xs text-gray-400">Recommended 200×200px. Max 2MB.</p>
        @error('photo')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>
