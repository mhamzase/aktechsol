@extends('layouts.app')

@section('title', 'Pricing | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Simple, transparent pricing packages from ' . ($siteSettings->site_name ?? 'AK Tech SOL') . '. Pick the package that fits your stage — every project starts with a free consultation.')

@push('seo')
<meta property="og:title" content="Pricing | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
<meta property="og:description" content="Transparent pricing packages for websites, apps and digital services. No hidden costs.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
@endpush

@section('content')
    <x-frontend.page-banner
        title="Pricing Packages"
        subtitle="Simple, transparent pricing — no hidden costs, ever."
    />

    <section class="py-16 sm:py-20 bg-slate-50/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.pricing-cards', ['packages' => $packages])

            @if($packages->isEmpty())
                <p class="text-center text-slate-500">Our pricing packages are being updated — please <a href="{{ route('quote.create') }}" class="text-[#1d4ed8] font-bold hover:underline">request a free quote</a> in the meantime.</p>
            @endif

            {{-- Custom need --}}
            <div class="mt-14 max-w-4xl mx-auto bg-gradient-to-r from-[#1d4ed8] to-[#12307e] rounded-3xl p-8 sm:p-10 text-center text-white">
                <h2 class="text-2xl sm:text-3xl font-extrabold">Need Something Custom?</h2>
                <p class="mt-3 text-blue-100/80">Every business is different. Tell us what you're building and we'll tailor a package and price just for you.</p>
                <div class="mt-7 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('quote.create') }}" class="px-7 py-3 rounded-xl bg-white text-[#12307e] font-bold hover:bg-blue-50 transition">Get a Free Quote</a>
                    <a href="{{ route('demo.create') }}" class="px-7 py-3 rounded-xl border-2 border-white/30 font-bold hover:bg-white/10 transition">Book a Free Demo</a>
                </div>
            </div>
        </div>
    </section>
@endsection
