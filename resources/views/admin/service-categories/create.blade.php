@extends('admin.layouts.app')

@section('title', 'Add Service Category')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Service Categories', 'url' => route('admin.service-categories.index')], ['label' => 'Add']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Add Service Category" subtitle="Create a new category" />

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
