@extends('admin.layouts.app')

@section('title', 'Add FAQ')

@section('content')
    <x-admin.page-header title="Add FAQ" subtitle="Create a new frequently asked question"
                        icon="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />

    <div class="max-w-3xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.faqs.store') }}" method="POST">
            @csrf
            @include('admin.faqs._form')
            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Create FAQ
                </button>
            </div>
        </form>
    </div>
@endsection
