{{-- Careers cards list — rendered on first load and returned by the AJAX load-more / search endpoint. --}}
@forelse($jobs as $job)
    @include('careers._card', ['job' => $job])
@empty
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-10 text-center">
        @if(!empty($search))
            <p class="text-lg font-bold text-slate-800">No positions match "{{ $search }}"</p>
            <p class="text-sm text-slate-500 mt-2">Try a different keyword, or clear the search to see all open positions.</p>
        @else
            <p class="text-lg font-bold text-slate-800">No open positions right now</p>
            <p class="text-sm text-slate-500 mt-2">We're not hiring at the moment, but new roles open up regularly — check back soon, or <a href="{{ route('contact') }}" class="text-[#1d4ed8] font-bold hover:underline">say hello anyway</a>.</p>
        @endif
    </div>
@endforelse
