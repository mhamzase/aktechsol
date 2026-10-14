{{--
    AJAX pagination — renders data-page links intercepted by AdminTable.
    Expects $items (a LengthAwarePaginator).
    Tailor-app style: "Showing X to Y of Z entries" + bordered page buttons.
--}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <p class="text-sm text-slate-500">
        @if ($items->total() > 0)
            Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} entries
        @else
            No records found
        @endif
    </p>
    {{-- Pagination buttons always visible (disabled on a single page) — tailor-app style --}}
    <nav class="flex flex-wrap items-center gap-1" aria-label="Pagination">
            {{-- Previous --}}
            @if ($items->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm font-medium text-slate-300 cursor-not-allowed">
                    <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Prev
                </span>
            @else
                <a href="#" data-page="{{ $items->currentPage() - 1 }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-600 hover:bg-slate-50">
                    <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>Prev
                </a>
            @endif

            @foreach ($items->getUrlRange(max(1, $items->currentPage() - 2), min($items->lastPage(), $items->currentPage() + 2)) as $page => $url)
                @if ($page == $items->currentPage())
                    <span class="px-3 py-1.5 rounded-lg bg-[#1669d9] text-white text-sm font-bold">{{ $page }}</span>
                @else
                    <a href="#" data-page="{{ $page }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-600 hover:bg-slate-50">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($items->hasMorePages())
                <a href="#" data-page="{{ $items->currentPage() + 1 }}" class="px-3 py-1.5 rounded-lg border border-slate-300 text-sm font-medium text-slate-600 hover:bg-slate-50">Next
                    <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm font-medium text-slate-300 cursor-not-allowed">Next
                    <svg class="w-3.5 h-3.5 inline -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </nav>
</div>
