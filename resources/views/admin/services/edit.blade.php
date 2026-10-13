@extends('admin.layouts.app')

@section('title', 'Edit Service')

@section('content')
<x-admin.page-header title="Edit Service" subtitle="Update service information"
                        icon="M13 10V3L4 14h7v7l9-11h-7z" />
    <div class="w-full mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.services._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Update Service
                </button>
            </div>
        </form>
    </div>
@endsection
