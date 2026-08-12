@extends('admin.layouts.app')

@section('title', 'Add Blog Category')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Blog Categories', 'url' => route('admin.blog-categories.index')], ['label' => 'Add']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Add Blog Category" subtitle="Create a new blog category" />

    <div class="max-w-4xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.blog-categories.store') }}" method="POST">
            @csrf
            @include('admin.blog-categories._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Create Category
                </button>
            </div>
        </form>
    </div>
@endsection
