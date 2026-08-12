@if ($paginator->hasPages())
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        {{-- Results info --}}
        <div class="text-sm text-gray-500">
            Showing
            <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
            to
            <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
            results
        </div>

        {{-- Pagination controls --}}
        <div class="flex items-center gap-2">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    &larr; Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-600 bg-white border border-gray-300 rounded-lg hover:bg-blue-50 transition">
                    &larr; Prev
                </a>
            @endif

            {{-- Page numbers --}}
            <div class="hidden md:flex items-center gap-1">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="px-3 py-2 text-sm text-gray-500">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="inline-flex items-center justify-center px-3 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}"
                                   class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-blue-50 hover:text-blue-600 transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-600 bg-white border border-gray-300 rounded-lg hover:bg-blue-50 transition">
                    Next &rarr;
                </a>
            @else
                <span class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    Next &rarr;
                </span>
            @endif
        </div>
    </div>
@endif
