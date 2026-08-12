@extends('layouts.admin')

@section('title', 'Edit Service')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Services', 'url' => route('admin.services.index')], ['label' => 'Edit']]" />
@endsection

@section('content')
<x-admin.page-banner
    title="Edit Service"
    subtitle="Update service information"
/>
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
