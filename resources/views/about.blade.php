@extends('layouts.app')

@section('title', $siteSettings->about_hero_title ?? 'About Us')
@section('meta_description', $siteSettings->about_hero_subtitle ?? 'Learn more about ' . ($siteSettings->site_name ??
    'AK Tech SOL'))

@section('content')
    {{-- Hero --}}
    <x-frontend.page-banner title="{{ $siteSettings->about_hero_title ?? 'About Us' }}"
        subtitle="{{ $siteSettings->about_hero_subtitle ?? '' }}" />

    {{-- Who We Are --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">{{ $siteSettings->about_intro_title ?? 'Who We Are' }}</h2>
                    <p class="mt-6 text-gray-600 leading-relaxed">{{ $siteSettings->about_intro_text }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-8 border border-gray-100">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">What We Do</h3>
                    <ul class="space-y-3 text-gray-600">
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-blue-600 rounded-full"></span> Custom
                            Web Development</li>
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-blue-600 rounded-full"></span> Mobile
                            App Development</li>
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-blue-600 rounded-full"></span> UI/UX
                            Design</li>
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-blue-600 rounded-full"></span> Cloud
                            Solutions</li>
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-blue-600 rounded-full"></span> Digital
                            Transformation</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission & Values --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">
                    {{ $siteSettings->about_mission_title ?? 'Our Mission & Values' }}</h2>
                <p class="mt-4 text-gray-500 max-w-2xl mx-auto">{{ $siteSettings->about_mission_subtitle }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 3; $i++)
                    @php
                        $cardTitle = $siteSettings->{"about_mission_card{$i}_title"} ?? 'Mission Card ' . $i;
                        $cardText = $siteSettings->{"about_mission_card{$i}_text"} ?? '';
                    @endphp
                    <div class="bg-white p-6 rounded-xl border border-gray-100">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                            {{-- Icon placeholder --}}
                            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $cardTitle }}</h3>
                        <p class="text-gray-500 text-sm">{{ $cardText }}</p>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">{{ $siteSettings->about_why_title ?? 'Why Choose Us' }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @for ($i = 1; $i <= 4; $i++)
                    @php
                        $itemTitle = $siteSettings->{"about_why_item{$i}_title"} ?? 'Why Item ' . $i;
                        $itemText = $siteSettings->{"about_why_item{$i}_text"} ?? '';
                    @endphp
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $itemTitle }}</h3>
                            <p class="text-gray-500 text-sm mt-1">{{ $itemText }}</p>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="bg-blue-50 text-gray-900 py-16">
        <div class="max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl font-bold text-blue-900">Let’s Work Together</h2>
            <p class="mt-4 text-blue-700">Have a project in mind? We’d love to hear from you.</p>
            <a href="{{ url('/contact') }}"
                class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">Contact
                Us</a>
        </div>
    </section>
@endsection
