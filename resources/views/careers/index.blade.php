@extends('layouts.app')

@section('title', 'Careers | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Join the ' . ($siteSettings->site_name ?? 'AK Tech SOL') . ' team — see open jobs and internships and apply with your portfolio.')

@push('seo')
<meta property="og:title" content="Careers | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
<meta property="og:description" content="Open jobs and internships — apply with your portfolio and grow with our team.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
@endpush

@section('content')
    <x-frontend.page-banner
        title="Careers"
        subtitle="Join our team — work on real projects for clients worldwide, from anywhere."
    />

    <section class="py-12 sm:py-16 bg-slate-50/60">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            @forelse($jobs as $job)
                <a href="{{ route('careers.show', $job->slug) }}" class="group flex flex-col sm:flex-row sm:items-center gap-4 bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-7 mb-4 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-100/50 transition-all">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-[0.7rem] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-full {{ $job->type === 'internship' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">{{ $job->type === 'internship' ? 'Internship' : 'Full-time Job' }}</span>
                            <span class="text-[0.7rem] font-extrabold uppercase tracking-wider px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 capitalize">{{ $job->location_type }}</span>
                            <span class="text-xs text-slate-400">{{ $job->created_at->format('M d, Y') }}</span>
                        </div>
                        <h2 class="text-xl font-extrabold text-slate-900 group-hover:text-[#1d4ed8] transition">{{ $job->title }}</h2>
                        <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">{{ \Illuminate\Support\Str::limit(strip_tags($job->description), 150) }}</p>
                    </div>
                    <span class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-gradient-to-r from-[#1d4ed8] to-[#2f6fed] text-white text-sm font-bold group-hover:shadow-lg group-hover:shadow-blue-600/25 transition">
                        View & Apply
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </span>
                </a>
            @empty
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-10 text-center">
                    <p class="text-lg font-bold text-slate-800">No open positions right now</p>
                    <p class="text-sm text-slate-500 mt-2">We're not hiring at the moment, but new roles open up regularly — check back soon, or <a href="{{ route('contact') }}" class="text-[#1d4ed8] font-bold hover:underline">say hello anyway</a>.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
