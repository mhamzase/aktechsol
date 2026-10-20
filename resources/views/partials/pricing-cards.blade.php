{{--
    Pricing cards — shared by the homepage pricing section and the
    /pricing page. Expects $packages (active ServicePackage models).
    The popular package renders as a highlighted blue card.
--}}
@if($packages->count())
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-stretch max-w-6xl mx-auto">
    @foreach($packages as $package)
        <div class="relative flex flex-col rounded-3xl p-8 {{ $package->is_popular ? 'bg-gradient-to-b from-[#1d4ed8] to-[#12307e] text-white shadow-2xl shadow-blue-600/30 md:-translate-y-3' : 'bg-white border border-slate-200/80 shadow-sm' }}">
            @if($package->is_popular)
                <span class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-amber-400 text-amber-900 text-[0.7rem] font-extrabold tracking-widest uppercase shadow whitespace-nowrap">Most Popular</span>
            @endif

            <h3 class="text-lg font-extrabold {{ $package->is_popular ? 'text-white' : 'text-slate-900' }}">{{ $package->name }}</h3>
            @if($package->description)
                <p class="mt-2 text-sm leading-relaxed {{ $package->is_popular ? 'text-blue-100/80' : 'text-slate-500' }}">{{ $package->description }}</p>
            @endif

            <div class="mt-6">
                @if($package->price_label)
                    <p class="text-xs font-semibold uppercase tracking-wider {{ $package->is_popular ? 'text-blue-200/70' : 'text-slate-400' }}">{{ $package->price_label }}</p>
                @endif
                <p class="text-4xl font-extrabold mt-1 {{ $package->is_popular ? 'text-white' : 'text-slate-900' }}">PKR {{ number_format($package->price) }}</p>
            </div>

            @if($package->features)
                <ul class="mt-7 space-y-3 text-sm flex-1 {{ $package->is_popular ? 'text-blue-50' : 'text-slate-600' }}">
                    @foreach($package->features as $feature)
                        <li class="flex items-start gap-2.5">
                            <svg class="w-5 h-5 shrink-0 {{ $package->is_popular ? 'text-amber-300' : 'text-green-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $feature }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if($package->turnaround)
                <p class="mt-6 flex items-center gap-2 text-xs font-medium {{ $package->is_popular ? 'text-blue-200/80' : 'text-slate-400' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Turnaround: {{ $package->turnaround }}
                </p>
            @endif

            <a href="{{ route('quote.create') }}" class="mt-7 block text-center px-6 py-3.5 rounded-xl font-bold transition {{ $package->is_popular ? 'bg-white text-[#12307e] hover:bg-blue-50' : 'bg-gradient-to-r from-[#1d4ed8] to-[#2f6fed] text-white hover:shadow-lg hover:shadow-blue-600/25' }}">
                Get Started
            </a>
        </div>
    @endforeach
</div>
@endif
