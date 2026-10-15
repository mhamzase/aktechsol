{{-- Media details drawer --}}
<div class="drawer-content p-6">
    {{-- Preview --}}
    <div class="mb-5 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
        @if (Str::startsWith($media->mime_type, 'image/'))
            <img src="{{ $media->getUrl() }}" alt="" class="w-full max-h-64 object-contain">
        @else
            <div class="py-12 text-center">
                <p class="text-3xl font-bold text-slate-400">{{ Str::upper(Str::afterLast($media->mime_type, '/')) }}</p>
                <p class="text-sm text-slate-500 mt-1">{{ $media->mime_type }}</p>
            </div>
        @endif
    </div>

    <div class="space-y-3">
        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">File Name</p>
            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1 break-all">{{ $media->file_name }}</p>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Collection</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $media->collection_name }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Size</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $media->human_readable_size }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Used By</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $media->model ? class_basename($media->model) . ' #' . $media->model->getKey() : '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Uploaded</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $media->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>
        <a href="{{ $media->getUrl() }}" target="_blank" rel="noopener" class="btn btn-secondary w-full">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Open Full File
        </a>
    </div>
</div>
