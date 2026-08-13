@extends('admin.layouts.app')

@section('title', 'Edit FAQ')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'FAQs', 'url' => route('admin.faqs.index')], ['label' => 'Edit']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Edit FAQ" subtitle="Update frequently asked question" />

    <div class="max-w-3xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.faqs._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Update FAQ
                </button>
            </div>
        </form>
    </div>
@endsection
