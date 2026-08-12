@extends('admin.layouts.app')

@section('title', 'Add Project')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Projects', 'url' => route('admin.projects.index')], ['label' => 'Add']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Add Project" subtitle="Create a new portfolio project" />

    <div class="max-w-5xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.projects._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Create Project
                </button>
            </div>
        </form>
    </div>
@endsection
