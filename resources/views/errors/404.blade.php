@extends('layouts.app')

@section('title', 'Page Not Found')
@section('meta_description', 'The page you are looking for could not be found.')

@section('content')
    <section class="py-20 bg-white">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <h1 class="text-9xl font-extrabold text-blue-600">404</h1>
            <h2 class="mt-4 text-3xl font-bold text-gray-900">Page Not Found</h2>
            <p class="mt-4 text-gray-500 max-w-md mx-auto">
                Sorry, the page you are looking for doesn't exist or has been moved.
            </p>
            <div class="mt-8">
                <a href="{{ url('/') }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Back to Home
                </a>
            </div>
        </div>
    </section>
@endsection
