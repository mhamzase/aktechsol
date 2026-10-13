@extends('admin.layouts.app')

@section('title', 'Add Testimonial')

@section('content')
    <x-admin.page-header title="Add Testimonial" subtitle="Create a new client testimonial"
                        icon="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />

    <div class="max-w-4xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.testimonials._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Create Testimonial
                </button>
            </div>
        </form>
    </div>
@endsection
