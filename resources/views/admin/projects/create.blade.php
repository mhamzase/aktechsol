@extends('admin.layouts.app')

@section('title', 'Add Project')

@section('content')
    <x-admin.page-header title="Add Project" subtitle="Create a new portfolio project"
                        icon="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />

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
