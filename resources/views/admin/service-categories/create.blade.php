@extends('admin.layouts.app')

@section('title', 'Add Service Category')

@section('content')
    <x-admin.page-header title="Add Service Category" subtitle="Create a new category"
                        icon="M7 7h.01M7 3h5.586a1 1 0 01.707.293l7.414 7.414a1 1 0 010 1.414l-5.414 5.414a1 1 0 01-1.414 0L5.879 10.12a1 1 0 01-.293-.707V7a2 2 0 012-2z" />

    <div class="max-w-4xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.service-categories.store') }}" method="POST">
            @csrf
            @include('admin.service-categories._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Create Category
                </button>
            </div>
        </form>
    </div>
@endsection
