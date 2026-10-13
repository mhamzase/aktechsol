@extends('admin.layouts.app')

@section('title', 'Add Blog Post')

@section('content')
    <x-admin.page-header title="Add Blog Post" subtitle="Create a new blog post"
                        icon="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />

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
