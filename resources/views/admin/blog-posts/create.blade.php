@extends('admin.layouts.app')

@section('title', 'Add Blog Post')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Blog Posts', 'url' => route('admin.blog-posts.index')], ['label' => 'Add']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Add Blog Post" subtitle="Create a new blog post" />

    <div class="max-w-5xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.blog-posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.blog-posts._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Create Post
                </button>
            </div>
        </form>
    </div>
@endsection
