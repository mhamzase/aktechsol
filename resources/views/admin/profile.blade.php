@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
    <x-admin.page-header title="My Profile" subtitle="Update your account information"
                        icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

    <div class="max-w-2xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-6">
                <h3 class="text-lg font-semibold text-gray-800">Profile Information</h3>

                {{-- Avatar --}}
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                            <span class="text-2xl font-semibold text-blue-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700 mb-1">Profile Photo</label>
                        <input type="file" name="avatar" id="avatar" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="mt-1 text-xs text-gray-400">Max 2MB. JPG, PNG, WEBP.</p>
                        @if($avatarUrl)
                            <label class="inline-flex items-center mt-2 text-sm text-red-600 cursor-pointer">
                                <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-red-600">
                                <span class="ml-1">Remove current photo</span>
                            </label>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('email') border-red-500 @enderror">
                        @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    Update Profile
                </button>
            </div>
        </form>

        {{-- Two-factor authentication (Fortify). Off by default — opt-in per user. --}}
        <div class="admin-card p-6 mt-6">
            <h3 class="text-lg font-bold text-[#0f2a5c]">Two-Factor Authentication</h3>
            <p class="text-sm text-slate-500 mt-1 mb-4">
                Add an extra security layer with an authenticator app (Google Authenticator, Authy…).
                Disabled by default — enable it only if you want it.
            </p>

            @if ($user->hasEnabledTwoFactorAuthentication())
                <p class="mb-3">{!! $user->twoFactorQrCodeSvg() !!}</p>
                <p class="text-xs text-slate-400 mb-4">Scan this QR code with your authenticator app, then keep your recovery codes safe:</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-4">
                    @foreach ($user->recoveryCodes() as $code)
                        <code class="text-xs bg-slate-100 rounded px-2 py-1.5 font-mono">{{ $code }}</code>
                    @endforeach
                </div>
                <div class="flex flex-wrap gap-2">
                    <form method="POST" action="{{ url('user/two-factor-recovery-codes') }}">
                        @csrf
                        <button class="btn btn-secondary">Regenerate Recovery Codes</button>
                    </form>
                    <form method="POST" action="{{ url('user/two-factor-authentication') }}"
                          onsubmit="return confirm('Disable two-factor authentication?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">Disable 2FA</button>
                    </form>
                </div>
            @else
                <form method="POST" action="{{ url('user/two-factor-authentication') }}">
                    @csrf
                    <button class="btn btn-primary">Enable Two-Factor Authentication</button>
                </form>
            @endif
        </div>
    </div>
@endsection
