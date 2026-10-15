{{-- Testimonial details drawer --}}
<div class="drawer-content p-6">
    <div class="flex items-start gap-4 mb-5">
        @if ($testimonial->getPhotoUrl())
            <img src="{{ $testimonial->getPhotoUrl() }}?v={{ $testimonial->updated_at->timestamp }}" alt="" class="h-16 w-16 rounded-full object-cover shrink-0 ring-2 ring-blue-100 dark:ring-blue-500/30">
        @else
            <span class="inline-flex h-16 w-16 rounded-full bg-blue-100 dark:bg-blue-500/20 text-[#1d4ed8] dark:text-blue-400 font-bold text-xl items-center justify-center shrink-0">{{ strtoupper(substr($testimonial->client_name, 0, 1)) }}</span>
        @endif
        <div class="min-w-0 flex-1">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $testimonial->client_name }}</h3>
            @if ($testimonial->client_position || $testimonial->company)
                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $testimonial->client_position }}{{ $testimonial->client_position && $testimonial->company ? ', ' : '' }}{{ $testimonial->company }}</p>
            @endif
            <p class="text-amber-400 text-sm mt-1">{{ str_repeat('★', $testimonial->rating) }}{{ str_repeat('☆', 5 - $testimonial->rating) }}</p>
        </div>
        <span class="shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $testimonial->status ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
            {{ $testimonial->status ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <div>
        <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Testimonial</p>
        <div class="bg-blue-50/60 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl p-4 text-slate-700 dark:text-slate-200 leading-relaxed text-[0.95rem] whitespace-pre-wrap">"{{ $testimonial->content }}"</div>
    </div>

    <div class="mt-5 bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5 inline-block">
        <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sort Order</p>
        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $testimonial->sort_order }}</p>
    </div>
</div>
