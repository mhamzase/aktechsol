@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Profile']]" />
@endsection

@section('content')
    <x-admin.page-banner title="My Profile" subtitle="Update your account information" />
    <div class="max-w-2xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :message="implode('<br>', $errors->all())" />
        @endif
        <div class="bg-white shadow-md rounded-lg p-6">

            <h2 class="text-xl font-semibold text-gray-800 mb-4">Profile Information</h2>
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <button type="submit"
                    class="inline-flex justify-center items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    Update Profile
                </button>
            </form>
        </div>
    </div>
@endsection
