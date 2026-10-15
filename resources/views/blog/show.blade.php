@extends('layouts.app')

@section('title', ($post->seo_title ?: $post->title . ' | ' . ($siteSettings->site_name ?? 'AK Tech SOL')))
@section('meta_description', $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? ''), 160))
@section('og_type', 'article')

@push('seo')
<meta property="og:title" content="{{ $post->seo_title ?: $post->title }}">
<meta property="og:description" content="{{ $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? ''), 160) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="article">
<meta property="article:published_time" content="{{ $post->published_at?->toIso8601String() }}">
@if($post->getFeaturedImageUrl())
<meta property="og:image" content="{{ $post->getFeaturedImageUrl() }}">
@endif
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BlogPosting",
    "headline": "{{ $post->title }}",
    "description": "{{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?? ''), 200) }}",
    "url": "{{ url()->current() }}",
    "datePublished": "{{ $post->published_at?->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at?->toIso8601String() }}",
    @if($post->getFeaturedImageUrl())
    "image": "{{ $post->getFeaturedImageUrl() }}",
    @endif
    "author": {
        "@@type": "Organization",
        "name": "{{ $siteSettings->site_name ?? 'AK Tech SOL' }}",
        "url": "{{ url('/') }}"
    },
    "publisher": {
        "@@type": "Organization",
        "name": "{{ $siteSettings->site_name ?? 'AK Tech SOL' }}",
        "url": "{{ url('/') }}"
    }
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        { "@@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
        { "@@type": "ListItem", "position": 2, "name": "Blog", "item": "{{ route('blog.index') }}" },
        { "@@type": "ListItem", "position": 3, "name": "{{ $post->title }}", "item": "{{ url()->current() }}" }
    ]
}
</script>
@endpush

@php
    $shareUrl = urlencode(url()->current());
    $shareText = urlencode($post->title);
@endphp

@section('content')
    <x-frontend.page-banner title="{{ $post->title }}" subtitle="{{ $post->category?->name ?? 'Blog' }}" />

    <section class="py-12 sm:py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-3 text-sm mb-6">
                <span class="px-3 py-1 bg-[#1d4ed8]/10 text-[#1d4ed8] text-xs font-bold rounded-full">{{ $post->category?->name ?? 'General' }}</span>
                <span class="text-slate-400">{{ $post->published_at?->format('M d, Y') }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-400">{{ ceil(str_word_count(strip_tags($post->content ?? '')) / 200) }} min read</span>
            </div>

            @if($post->getFeaturedImageUrl())
                <img src="{{ $post->getFeaturedImageUrl() }}" alt="{{ $post->title }}"
                     class="w-full h-64 sm:h-96 object-cover rounded-3xl mb-8 shadow-lg">
            @endif

            @if($post->excerpt)
                <p class="text-lg text-slate-600 leading-relaxed border-l-4 border-[#1d4ed8] pl-5 mb-8 italic">{{ $post->excerpt }}</p>
            @endif

            <article class="prose prose-slate prose-lg max-w-none leading-relaxed">
                {!! $post->content !!}
            </article>

            {{-- Share --}}
            <div class="mt-10 pt-8 border-t border-slate-100 flex flex-wrap items-center gap-3">
                <span class="text-sm font-bold text-slate-700">Share this article:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"
                   class="w-10 h-10 rounded-xl bg-[#1877F2] text-white flex items-center justify-center hover:opacity-90 transition" aria-label="Share on Facebook">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareText }}" target="_blank" rel="noopener"
                   class="w-10 h-10 rounded-xl bg-black text-white flex items-center justify-center hover:opacity-90 transition" aria-label="Share on X">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener"
                   class="w-10 h-10 rounded-xl bg-[#0A66C2] text-white flex items-center justify-center hover:opacity-90 transition" aria-label="Share on LinkedIn">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener"
                   class="w-10 h-10 rounded-xl bg-[#25D366] text-white flex items-center justify-center hover:opacity-90 transition" aria-label="Share on WhatsApp">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </a>
                <button onclick="copyPostLink()" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-slate-200 transition" aria-label="Copy link">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
            </div>

            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 mt-8 text-[#1d4ed8] font-bold hover:gap-3 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Blog
            </a>
        </div>
    </section>

    @if($related->count() > 0)
    <section class="py-12 sm:py-16 bg-slate-50/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-sm font-bold text-[#1d4ed8] uppercase tracking-widest">Keep reading</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">Related Articles</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($related->take(3) as $relatedPost)
                    <a href="{{ route('blog.show', $relatedPost->slug) }}" class="group bg-white rounded-2xl overflow-hidden border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        @if($relatedPost->getFeaturedImageUrl())
                            <img src="{{ $relatedPost->getFeaturedImageUrl() }}" alt="{{ $relatedPost->title }}" loading="lazy" class="h-44 w-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="h-44 bg-gradient-to-br from-slate-50 to-slate-100"></div>
                        @endif
                        <div class="p-5">
                            <p class="text-xs text-[#1d4ed8] font-bold">{{ $relatedPost->category?->name ?? 'General' }}</p>
                            <h3 class="font-bold text-slate-900 mt-1 line-clamp-2 group-hover:text-[#1d4ed8] transition">{{ $relatedPost->title }}</h3>
                            <p class="text-xs text-slate-400 mt-2">{{ $relatedPost->published_at?->format('M d, Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection

@push('scripts')
<script>
    function copyPostLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        });
    }
</script>
@endpush
