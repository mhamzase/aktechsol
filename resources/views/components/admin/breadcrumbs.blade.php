@props(['items' => []])
@if (count($items))
    <nav class="text-sm" aria-label="Breadcrumb">
        <ol class="list-none p-1.5 inline-flex items-center flex-wrap gap-1 bg-gray-100 rounded-full">
            @foreach ($items as $key => $item)
                <li class="flex items-center gap-1">
                    @if ($key > 0)
                        <svg class="h-4 w-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    @endif
                    @if (isset($item['url']))
                        <a href="{{ $item['url'] }}" class="text-gray-500 font-medium px-3 py-1.5 rounded-full transition-colors hover:text-gray-900 hover:bg-white">
                            @if ($key === 0)
                                <svg class="h-4 w-4 inline-block -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            @else
                                {{ $item['label'] }}
                            @endif
                        </a>
                    @else
                        <span class="text-blue-600 font-semibold bg-white px-3 py-1.5 rounded-full shadow-sm">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
