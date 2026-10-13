@extends('admin.layouts.app')

@section('title', 'Change Password')

@section('content')
    <x-admin.page-header title="Change Password" subtitle="Keep your account secure"
                        icon="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />

    <div class="max-w-2xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.change-password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="text-lg font-semibold text-gray-800">Update Password</h3>

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1.5">Current Password <span class="text-red-500">*</span></label>
                    <input type="password" name="current_password" id="current_password" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('current_password') border-red-500 @enderror">
                    @error('current_password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1.5">New Password <span class="text-red-500">*</span></label>
                    <input type="password" name="new_password" id="new_password" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('new_password') border-red-500 @enderror">
                    @error('new_password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm New Password <span class="text-red-500">*</span></label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                           class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Update Password
                </button>
            </div>
        </form>
    </div>
@endsection
