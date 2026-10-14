{{--
    Admin profile — avatar drag & drop (AJAX), profile info (AJAX),
    two-factor authentication with confirm flow (AJAX).
--}}
@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
    <x-admin.page-header title="My Profile" subtitle="Update your account information"
                        icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

    <div class="max-w-2xl mx-auto space-y-6" id="profile-root"
         data-avatar-url="{{ route('admin.profile.avatar') }}"
         data-tfa-status="{{ route('admin.profile.2fa.status') }}"
         data-tfa-enable="{{ route('admin.profile.2fa.enable') }}"
         data-tfa-confirm="{{ route('admin.profile.2fa.confirm') }}"
         data-tfa-disable="{{ route('admin.profile.2fa.disable') }}"
         data-tfa-regenerate="{{ route('admin.profile.2fa.codes.regenerate') }}">
        {{-- ============ Profile information ============ --}}
        <div class="admin-card p-6">
            <h3 class="text-lg font-bold text-[#0f2a5c]">Profile Information</h3>

            {{-- Avatar drag & drop zone --}}
            <div class="mt-5">
                <div id="avatar-dropzone"
                     class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center cursor-pointer transition-colors hover:border-[#1d4ed8] hover:bg-blue-50/50">
                    <div class="w-24 h-24 mx-auto rounded-full overflow-hidden bg-blue-100 flex items-center justify-center ring-4 ring-blue-50">
                        @if ($avatarUrl)
                            <img id="avatar-preview" src="{{ $avatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                            <span id="avatar-initial" class="hidden text-3xl font-bold text-blue-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @else
                            <img id="avatar-preview" src="" alt="Avatar" class="w-full h-full object-cover hidden">
                            <span id="avatar-initial" class="text-3xl font-bold text-blue-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <p class="mt-3 text-sm text-slate-600">
                        Drag &amp; drop your photo here, or
                        <span class="font-semibold text-[#1d4ed8]">browse files</span>
                    </p>
                    <p class="mt-1 text-xs text-slate-400">JPG, PNG, WEBP · max 2MB · uploads instantly</p>
                    <input type="file" id="avatar-input" accept="image/jpeg,image/png,image/webp" class="hidden">
                </div>
                <div class="mt-2 flex items-center justify-between min-h-[1.75rem]">
                    <p id="avatar-status" class="text-xs text-slate-400"></p>
                    @if ($avatarUrl)
                        <button type="button" id="avatar-remove" class="text-xs font-semibold text-red-600 hover:text-red-700">Remove photo</button>
                    @else
                        <button type="button" id="avatar-remove" class="hidden text-xs font-semibold text-red-600 hover:text-red-700">Remove photo</button>
                    @endif
                </div>
            </div>

            {{-- Name / email (AJAX) --}}
            <form id="profile-form" action="{{ route('admin.profile.update') }}" method="POST" class="mt-4 space-y-5" novalidate>
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-ui.input name="name" label="Name" :value="$user->name" required />
                    <x-ui.input name="email" type="email" label="Email" :value="$user->email" required />
                </div>
                <div class="flex justify-end">
                    <button type="submit" id="profile-save-btn" class="btn btn-primary px-6 py-2.5">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>

        {{-- ============ Two-factor authentication ============ --}}
        <div class="admin-card p-6">
            <div class="flex items-center justify-between gap-3">
                <h3 class="text-lg font-bold text-[#0f2a5c]">Two-Factor Authentication</h3>
                <span id="tfa-badge"></span>
            </div>
            <div id="tfa-body" class="mt-4">
                <div class="flex items-center gap-3 text-sm text-slate-400">
                    <span class="spin inline-block">◌</span> Loading…
                </div>
            </div>
        </div>
    </div>

    {{-- ============ Generic confirm modal ============ --}}
    <div id="pf-confirm-modal" class="hidden fixed inset-0 z-[70] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50" data-pf-close></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
            <h4 id="pf-cm-title" class="text-base font-bold text-slate-800"></h4>
            <p id="pf-cm-text" class="mt-2 text-sm text-slate-500"></p>
            <div class="mt-5 flex justify-end gap-2">
                <button type="button" data-pf-close class="btn btn-secondary">Cancel</button>
                <button type="button" id="pf-cm-ok" class="btn btn-danger">Confirm</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/admin-profile.js') }}"></script>
@endpush
