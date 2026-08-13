@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-admin.page-banner
        title="Dashboard"
        subtitle="Welcome back, {{ auth()->user()->name }}!"
        stats="{{ $stats['services'] }} services | {{ $stats['projects'] }} projects"
    />

    {{-- Stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Services --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['services'] }}</p>
                <p class="text-sm text-gray-500 font-medium">Services</p>
            </div>
        </div>

        {{-- Projects --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['projects'] }}</p>
                <p class="text-sm text-gray-500 font-medium">Projects</p>
            </div>
        </div>

        {{-- Blog Posts --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['blog_posts'] }}</p>
                <p class="text-sm text-gray-500 font-medium">Blog Posts</p>
            </div>
        </div>

        {{-- Testimonials --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['testimonials'] }}</p>
                <p class="text-sm text-gray-500 font-medium">Testimonials</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.services.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 transition">
                + New Service
            </a>
            <a href="{{ route('admin.projects.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                + New Project
            </a>
            <a href="{{ route('admin.blog-posts.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700 transition">
                + New Blog Post
            </a>
            <a href="{{ route('admin.testimonials.create') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-amber-600 text-white text-sm font-semibold hover:bg-amber-700 transition">
                + New Testimonial
            </a>
        </div>
    </div>

    {{-- Recent Contact Messages --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Recent Contact Messages</h3>
            <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
        </div>
        <div class="space-y-3">
            @forelse($recentMessages as $message)
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $message->name }}</p>
                        <p class="text-xs text-gray-500">{{ $message->subject }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-500">No messages yet.</p>
            @endforelse
        </div>
    </div>
@endsection
