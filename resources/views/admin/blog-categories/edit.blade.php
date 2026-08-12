@extends('admin.layouts.app')

@section('title', 'Edit Blog Category')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Blog Categories', 'url' => route('admin.blog-categories.index')], ['label' => 'Edit']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Edit Blog Category" subtitle="Update blog category" />

    <div class="max-w-4xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.blog-categories.update', $blogCategory) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.blog-categories._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Update Category
                </button>
            </div>
        </form>
    </div>
@endsection
