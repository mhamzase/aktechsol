@extends('layouts.app')

@section('title', 'Privacy Policy')
@section('meta_description', 'Privacy Policy for ' . ($siteSettings->site_name ?? 'AK Tech SOL'))

@section('content')
    <x-frontend.page-banner
        title="Privacy Policy"
        subtitle="How we collect, use, and protect your information."
    />

    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! $siteSettings->privacy_policy_content !!}
            </div>
        </div>
    </section>
@endsection
