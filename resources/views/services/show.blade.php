@extends('layouts.app')

@section('title', $service->seo_title ?? $service->title)
@section('meta_description', $service->meta_description)

@section('content')
<x-frontend.page-banner
    title="{{ $service->seo_title ?? $service->title }}"
    subtitle="{{ $service->short_description }}"
/>
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            @if($service->getThumbnailUrl())
                <img src="{{ $service->getThumbnailUrl() }}" alt="{{ $service->title }}" class="w-full h-64 object-cover rounded-xl mb-8">
            @endif
            <h1 class="text-4xl font-bold text-gray-900">{{ $service->title }}</h1>
            @if($service->category)
                <p class="text-blue-600 font-medium mt-2">{{ $service->category->name }}</p>
            @endif
            <div class="mt-8 text-gray-700 leading-relaxed">
                {!! nl2br(e($service->full_description)) !!}
            </div>
            <a href="{{ route('services.index') }}" class="inline-block mt-8 text-blue-600 hover:underline">&larr; Back to Services</a>
        </div>
    </section>
@endsection
