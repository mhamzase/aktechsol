{{-- Review details drawer --}}
<div class="drawer-content">
    <div class="px-6 pb-6 pt-2 space-y-5">
        {{-- Hero --}}
        <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #1d4ed8 0%, #12307e 100%);">
            <div class="flex items-center gap-1 mb-2">{!! $review->starsHtml() !!}</div>
            <p class="text-lg font-extrabold leading-snug">{{ $review->reviewer_name }}</p>
            <p class="text-blue-200/80 text-xs mt-1">{{ $review->created_at->format('M d, Y h:i A') }}</p>
        </div>

        {{-- Meta --}}
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div>
                <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-wider">Status</p>
                <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5">{{ $review->statusLabel() }}</p>
            </div>
            <div>
                <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-wider">Type</p>
                <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5 capitalize">{{ $review->reviewer_type }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-wider">Project</p>
                @if($review->project)
                <a href="{{ $review->project->trackingUrl() }}" target="_blank"
                   class="font-bold text-blue-600 hover:text-blue-700 mt-0.5 inline-block">{{ $review->project->title }}</a>
                <p class="text-xs text-slate-400">{{ $review->project->client_name }}</p>
                @else
                <p class="font-bold text-slate-800 dark:text-slate-100 mt-0.5">—</p>
                @endif
            </div>
        </div>

        {{-- Review text --}}
        <div>
            <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-wider mb-2">Review</p>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ $review->review ?: 'No review text yet.' }}</div>
        </div>

        {{-- Actions --}}
        @if($review->status === 'pending')
        <div class="flex gap-2">
            <button type="button" data-approve-review data-url="{{ route('admin.reviews.approve', $review) }}"
                    class="flex-1 px-4 py-3 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition text-sm">
                Approve &amp; Publish
            </button>
            <button type="button" data-reject-review data-url="{{ route('admin.reviews.reject', $review) }}"
                    class="flex-1 px-4 py-3 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition text-sm">
                Reject
            </button>
        </div>
        @endif
    </div>
</div>
