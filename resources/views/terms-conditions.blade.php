@extends('layouts.app')

@section('title', 'Terms & Conditions')
@section('meta_description', 'Terms & Conditions for ' . ($siteSettings->site_name ?? 'AK Tech SOL'))

@section('content')
    <x-frontend.page-banner
        title="Terms & Conditions"
        subtitle="Please read these terms carefully before using our services."
    />

    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="prose max-w-none text-gray-700 leading-relaxed">
                {!! $siteSettings->terms_conditions_content !!}
            </div>
        </div>
    </section>
@endsection
