@extends('layouts.admin')

@section('title', 'Change Password')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Change Password']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Change Password" subtitle="Keep your account secure" />
    <div class="max-w-2xl mx-auto">
        {{-- Validation errors --}}
        @if ($errors->any())
            <x-admin.alert type="error" :message="implode('<br>', $errors->all())" />
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Change Your Password</h2>
            <form action="{{ route('admin.change-password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current
                        Password</label>
                    <input id="current_password" name="current_password" type="password" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input id="new_password" name="new_password" type="password" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('new_password') border-red-500 @enderror">
                    @error('new_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New
                        Password</label>
                    <input id="new_password_confirmation" name="new_password_confirmation" type="password" required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('new_password_confirmation') border-red-500 @enderror">
                    @error('new_password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="inline-flex justify-center items-center px-5 py-2.5 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    Update Password
                </button>
            </form>
        </div>
    </div>
@endsection
