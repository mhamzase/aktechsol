{{--
    AJAX pagination — renders data-page links intercepted by AdminTable.
    Expects $items (a LengthAwarePaginator).
    Shows "Showing X–Y of Z" count on the left, page buttons on the right.
--}}
<div class="flex flex-wrap items-center justify-between gap-3">
    <p class="text-xs font-medium text-slate-400">
        @if ($items->total() > 0)
            Showing {{ $items->firstItem() }}–{{ $items->lastItem() }} of {{ $items->total() }}
        @else
            No records found
        @endif
    </p>
    @if ($items->hasPages())
        <nav class="flex items-center gap-1 text-sm" aria-label="Pagination">
            {{-- Previous --}}
            @if ($items->onFirstPage())
                <span class="px-3 py-1.5 rounded-lg text-slate-300 cursor-not-allowed">‹ Prev</span>
            @else
                <a href="#" data-page="{{ $items->currentPage() - 1 }}" class="px-3 py-1.5 rounded-lg text-slate-600 hover:bg-blue-50 hover:text-[#1669d9] font-medium">‹ Prev</a>
            @endif

            @foreach ($items->getUrlRange(max(1, $items->currentPage() - 2), min($items->lastPage(), $items->currentPage() + 2)) as $page => $url)
                @if ($page == $items->currentPage())
                    <span class="px-3 py-1.5 rounded-lg bg-[#1669d9] text-white font-bold">{{ $page }}</span>
                @else
                    <a href="#" data-page="{{ $page }}" class="px-3 py-1.5 rounded-lg text-slate-600 hover:bg-blue-50 hover:text-[#1669d9] font-medium">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($items->hasMorePages())
                <a href="#" data-page="{{ $items->currentPage() + 1 }}" class="px-3 py-1.5 rounded-lg text-slate-600 hover:bg-blue-50 hover:text-[#1669d9] font-medium">Next ›</a>
            @else
                <span class="px-3 py-1.5 rounded-lg text-slate-300 cursor-not-allowed">Next ›</span>
            @endif
        </nav>
    @endif
</div>
