@extends('admin.layouts.app')

@section('title', 'Edit Testimonial')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Testimonials', 'url' => route('admin.testimonials.index')], ['label' => 'Edit']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Edit Testimonial" subtitle="Update client testimonial" />

    <div class="max-w-4xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.testimonials._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Update Testimonial
                </button>
            </div>
        </form>
    </div>
@endsection
