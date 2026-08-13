@extends('layouts.app')

@section('title', $project->seo_title ?? $project->title)
@section('meta_description', $project->meta_description)

@section('content')
    <x-frontend.page-banner title="{{ $project->seo_title ?? $project->title }}"
        subtitle="{{ $project->short_description }}" />
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            @if ($project->getFeaturedImageUrl())
                <img src="{{ $project->getFeaturedImageUrl() }}" alt="{{ $project->title }}"
                    class="w-full h-64 object-cover rounded-xl mb-8">
            @endif
            <h1 class="text-4xl font-bold text-gray-900">{{ $project->title }}</h1>
            @if ($project->client_name)
                <p class="text-gray-500 mt-2">Client: {{ $project->client_name }}</p>
            @endif
            <div class="mt-8 text-gray-700 leading-relaxed">
                {!! nl2br(e($project->full_description)) !!}
            </div>
            @if ($project->project_url)
                <a href="{{ $project->project_url }}" target="_blank"
                    class="inline-block mt-6 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">Visit
                    Project</a>
            @endif
            @if (count($gallery))
                <div class="mt-8">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-4">Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($gallery as $img)
                            <img src="{{ $img->getUrl() }}" class="rounded-lg object-cover w-full h-32">
                        @endforeach
                    </div>
                </div>
            @endif
            <a href="{{ route('projects.index') }}" class="inline-block mt-8 text-blue-600 hover:underline">&larr; Back to
                Projects</a>
        </div>
    </section>
@endsection
