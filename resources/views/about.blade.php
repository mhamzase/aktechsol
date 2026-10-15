@extends('layouts.app')

@section('title', ($siteSettings->about_hero_title ?? 'About Us') . ' | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', $siteSettings->about_hero_subtitle ?? 'Learn more about ' . ($siteSettings->site_name ?? 'AK Tech SOL') . ' — our mission, values and team.')

@section('content')
    <x-frontend.page-banner title="{{ $siteSettings->about_hero_title ?? 'About Us' }}"
        subtitle="{{ $siteSettings->about_hero_subtitle ?? 'The team behind your digital success.' }}" />

    {{-- Who We Are --}}
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="text-sm font-bold text-[#1d4ed8] uppercase tracking-widest">Who we are</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">{{ $siteSettings->about_intro_title ?? 'Who We Are' }}</h2>
                    <p class="mt-6 text-slate-500 leading-relaxed">{{ $siteSettings->about_intro_text }}</p>
                    <p class="mt-4 text-slate-500 leading-relaxed">We work with clients <strong class="text-slate-800">all over the world</strong> — from startups to enterprises, across every timezone. Wherever you are, we deliver the same quality, communication, and commitment.</p>
                    <div class="mt-8 grid grid-cols-3 gap-6">
                        <div>
                            <p class="text-3xl font-extrabold text-[#1d4ed8]">50+</p>
                            <p class="text-sm text-slate-400 mt-1">Projects</p>
                        </div>
                        <div>
                            <p class="text-3xl font-extrabold text-[#1d4ed8]">15+</p>
                            <p class="text-sm text-slate-400 mt-1">Countries</p>
                        </div>
                        <div>
                            <p class="text-3xl font-extrabold text-[#1d4ed8]">5★</p>
                            <p class="text-sm text-slate-400 mt-1">Rating</p>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center gap-2 text-sm text-slate-500">
                        <svg class="w-5 h-5 text-[#1d4ed8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="font-medium">Working across all timezones — US, UK, EU, Middle East & beyond</span>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-[#1d4ed8]/5 to-[#1d4ed8]/10 rounded-3xl p-8 border border-blue-100">
                    <h3 class="text-xl font-extrabold text-slate-900 mb-5">What We Do</h3>
                    <ul class="space-y-4">
                        @foreach(['Custom Web Development', 'Mobile App Development', 'UI/UX Design', 'Cloud Solutions', 'Digital Transformation'] as $item)
                            <li class="flex items-center gap-3 bg-white rounded-xl px-4 py-3 shadow-sm">
                                <span class="w-8 h-8 rounded-lg bg-[#1d4ed8]/10 text-[#1d4ed8] flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </span>
                                <span class="font-semibold text-slate-800 text-sm">{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission & Values --}}
    <section class="py-16 sm:py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-sm font-bold text-[#1d4ed8] uppercase tracking-widest">Our foundation</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">{{ $siteSettings->about_mission_title ?? 'Our Mission & Values' }}</h2>
                <p class="mt-4 text-slate-500 max-w-2xl mx-auto">{{ $siteSettings->about_mission_subtitle }}</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @for ($i = 1; $i <= 3; $i++)
                    @php
                        $cardTitle = $siteSettings->{"about_mission_card{$i}_title"} ?? 'Mission Card ' . $i;
                        $cardText = $siteSettings->{"about_mission_card{$i}_text"} ?? '';
                    @endphp
                    <div class="bg-white p-7 rounded-2xl border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="w-13 h-13 w-[52px] h-[52px] bg-gradient-to-br from-[#1d4ed8] to-[#12307e] rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-blue-600/20">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $cardTitle }}</h3>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ $cardText }}</p>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-16 sm:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span class="text-sm font-bold text-[#1d4ed8] uppercase tracking-widest">Why us</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2">{{ $siteSettings->about_why_title ?? 'Why Choose Us' }}</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                @for ($i = 1; $i <= 4; $i++)
                    @php
                        $itemTitle = $siteSettings->{"about_why_item{$i}_title"} ?? 'Why Item ' . $i;
                        $itemText = $siteSettings->{"about_why_item{$i}_text"} ?? '';
                    @endphp
                    <div class="flex items-start gap-4 bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <div class="w-11 h-11 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900">{{ $itemTitle }}</h3>
                            <p class="text-slate-500 text-sm mt-1">{{ $itemText }}</p>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="relative py-16 sm:py-20 bg-gradient-to-r from-[#1d4ed8] to-[#12307e] text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 26px 26px;"></div>
        <div class="relative max-w-4xl mx-auto text-center px-4">
            <h2 class="text-3xl sm:text-4xl font-extrabold">Let's Work Together</h2>
            <p class="mt-4 text-blue-100/80 text-lg">Have a project in mind? We'd love to hear from you.</p>
            <a href="{{ url('/contact') }}" class="mt-8 inline-block px-8 py-3.5 bg-white text-[#12307e] font-bold rounded-2xl hover:bg-blue-50 transition shadow-xl">Contact Us</a>
        </div>
    </section>
@endsection
