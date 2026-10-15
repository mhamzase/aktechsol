@extends('layouts.app')

@section('title', ($service->seo_title ?: $service->title . ' | ' . ($siteSettings->site_name ?? 'AK Tech SOL')))
@section('meta_description', $service->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($service->short_description), 160))
@section('og_type', 'article')

@push('seo')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Service",
    "name": "{{ $service->title }}",
    "description": "{{ \Illuminate\Support\Str::limit(strip_tags($service->short_description ?? ''), 200) }}",
    "url": "{{ url()->current() }}",
    "provider": {
        "@@type": "Organization",
        "name": "{{ $siteSettings->site_name ?? 'AK Tech SOL' }}",
        "url": "{{ url('/') }}"
    }
    @if($service->category)
    ,
    "serviceType": "{{ $service->category->name }}"
    @endif
}
</script>
{{-- Breadcrumb schema --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Services", "item": "{{ route('services.index') }}" },
        { "@@type": "ListItem", "position": 3, "name": "{{ $service->title }}", "item": "{{ url()->current() }}" }
    ]
}
</script>
@endpush

@php
    $shareUrl = urlencode(url()->current());
    $shareText = urlencode($service->title);
@endphp

@section('content')
<x-frontend.page-banner
    title="{{ $service->title }}"
    subtitle="{{ $service->short_description }}"
/>

    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-10">
                {{-- Main content --}}
                <div class="lg:col-span-2">
                    @if($service->getThumbnailUrl())
                        <img src="{{ $service->getThumbnailUrl() }}" alt="{{ $service->title }}" class="w-full h-64 sm:h-80 object-cover rounded-2xl mb-8 shadow-lg">
                    @endif

                    @if($service->category)
                        <a href="{{ route('services.index', ['category' => $service->category->slug]) }}" class="inline-block text-xs font-bold text-[#1d4ed8] uppercase tracking-widest bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">{{ $service->category->name }}</a>
                    @endif

                    <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3">{{ $service->title }}</h1>

                    <div class="mt-6 text-slate-600 leading-relaxed prose prose-slate max-w-none">
                        {!! nl2br(e($service->full_description)) !!}
                    </div>

                    {{-- Share --}}
                    <div class="mt-10 pt-6 border-t border-slate-100">
                        <p class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Share this service</p>
                        <div class="flex flex-wrap gap-2.5">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#1877f2]/10 text-[#1877f2] text-sm font-semibold hover:bg-[#1877f2] hover:text-white transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Facebook
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900/10 text-slate-900 text-sm font-semibold hover:bg-slate-900 hover:text-white transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                X
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0a66c2]/10 text-[#0a66c2] text-sm font-semibold hover:bg-[#0a66c2] hover:text-white transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                LinkedIn
                            </a>
                            <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#25d366]/10 text-[#128c4b] text-sm font-semibold hover:bg-[#25d366] hover:text-white transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                WhatsApp
                            </a>
                            <button onclick="navigator.clipboard.writeText('{{ url()->current() }}').then(() => this.querySelector('span').textContent = 'Copied!')" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold hover:bg-slate-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span>Copy Link</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="lg:sticky lg:top-28 space-y-6">
                        {{-- CTA card --}}
                        <div class="bg-gradient-to-br from-[#0a1f44] to-[#1d4ed8] rounded-2xl p-6 text-white">
                            <h3 class="font-extrabold text-lg">Need this service?</h3>
                            <p class="text-sm text-blue-100/80 mt-2">Get a free quote for your project today.</p>
                            <a href="{{ url('/contact') }}" class="mt-4 block text-center px-5 py-3 rounded-xl bg-white text-[#12307e] font-bold hover:bg-blue-50 transition">Get a Free Quote</a>
                        </div>

                        {{-- Back link --}}
                        <a href="{{ route('services.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-[#1d4ed8] transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Back to Services
                        </a>
                    </div>
                </div>
            </div>

            {{-- Related services --}}
            @if($related->count())
            <div class="mt-16">
                <h2 class="text-2xl font-extrabold text-slate-900 mb-6">Related Services</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($related as $rel)
                        <a href="{{ route('services.show', $rel->slug) }}" class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <div class="p-6">
                                @if($rel->category)
                                    <span class="text-[0.7rem] font-bold text-[#1d4ed8] uppercase tracking-widest">{{ $rel->category->name }}</span>
                                @endif
                                <h3 class="font-bold text-slate-900 mt-1.5 group-hover:text-[#1d4ed8] transition-colors">{{ $rel->title }}</h3>
                                <p class="text-sm text-slate-500 mt-2 line-clamp-2">{{ $rel->short_description }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>
@endsection
