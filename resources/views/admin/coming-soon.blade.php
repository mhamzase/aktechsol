{{-- Placeholder for modules landing in later phases. --}}
@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <x-admin.page-header :title="$title"
                        :subtitle="'Coming soon — ships in ' . $phase"
                        icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

    <div class="admin-card p-10 text-center max-w-xl mx-auto">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-blue-100 flex items-center justify-center mb-4">
            <svg class="w-8 h-8 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-lg font-bold text-[#0f2a5c] mb-1">{{ $title }}</h2>
        <p class="text-sm text-slate-500 mb-1">This module ships in <strong>{{ $phase }}</strong> of the build plan.</p>
        <p class="text-xs text-slate-400">Everything else in the panel keeps working until then.</p>
    </div>
@endsection
