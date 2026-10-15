{{-- FAQ details drawer --}}
<div class="drawer-content p-6">
    <div class="flex items-start gap-3 mb-5">
        <span class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/20 text-[#1d4ed8] dark:text-blue-400 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <div class="min-w-0 flex-1">
            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $faq->question }}</h3>
        </div>
        <span class="shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $faq->status ? 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400' }}">
            {{ $faq->status ? 'Active' : 'Inactive' }}
        </span>
    </div>

    <div>
        <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Answer</p>
        <div class="bg-blue-50/60 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl p-4 text-slate-700 dark:text-slate-200 leading-relaxed text-[0.95rem] prose prose-sm dark:prose-invert max-w-none">{!! $faq->answer !!}</div>
    </div>

    <div class="mt-5 bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5 inline-block">
        <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sort Order</p>
        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $faq->sort_order }}</p>
    </div>
</div>
