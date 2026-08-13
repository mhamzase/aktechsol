<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 space-y-5">
    <div>
        <label for="question" class="block text-sm font-medium text-gray-700 mb-1.5">Question <span class="text-red-500">*</span></label>
        <input type="text" name="question" id="question" value="{{ old('question', $faq->question ?? '') }}" required
               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('question') border-red-500 @enderror">
        @error('question')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="answer" class="block text-sm font-medium text-gray-700 mb-1.5">Answer <span class="text-red-500">*</span></label>
        <textarea name="answer" id="answer" rows="6" required
                  class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('answer') border-red-500 @enderror">{{ old('answer', $faq->answer ?? '') }}</textarea>
        @error('answer')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1.5">Sort Order</label>
            <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $faq->sort_order ?? 0) }}"
                   class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
            <select name="status" id="status"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="1" {{ old('status', $faq->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                <option value="0" {{ old('status', $faq->status ?? 1) == 0 ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
</div>
