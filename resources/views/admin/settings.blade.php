@extends('layouts.admin')

@section('title', 'Site Settings')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Settings']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Site Settings" subtitle="Manage your website configuration" stats="4 configurable options" />
    <div class="max-w-4xl mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex flex-wrap -mb-px gap-1" id="settingsTabs" role="tablist">
                    <button type="button"
                        class="tab-link active inline-flex items-center px-4 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600"
                        data-tab="general" role="tab">
                        General
                    </button>
                    <button type="button"
                        class="tab-link inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300"
                        data-tab="branding" role="tab">
                        Branding
                    </button>
                    <button type="button"
                        class="tab-link inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300"
                        data-tab="footer" role="tab">
                        Footer
                    </button>
                    <button type="button"
                        class="tab-link inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300"
                        data-tab="social" role="tab">
                        Social Links
                    </button>
                </nav>
            </div>

            <!-- Tab Panels -->
            <div id="tabContent">
                {{-- General Tab --}}
                <div id="tab-general" class="tab-panel">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">General Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Website
                                    Name</label>
                                <input type="text" name="site_name" id="site_name"
                                    value="{{ old('site_name', $settings->site_name) }}" required
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('site_name') border-red-500 @enderror">
                                @error('site_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="site_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="site_email" id="site_email"
                                    value="{{ old('site_email', $settings->site_email) }}" required
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('site_email') border-red-500 @enderror">
                                @error('site_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="site_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="site_phone" id="site_phone"
                                    value="{{ old('site_phone', $settings->site_phone) }}"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="site_address"
                                    class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea name="site_address" id="site_address" rows="2"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('site_address', $settings->site_address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Branding Tab --}}
                <div id="tab-branding" class="tab-panel hidden">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Branding</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo</label>
                                @if ($logoUrl)
                                    <div class="mb-2">
                                        <img src="{{ $logoUrl }}" alt="Logo"
                                            class="h-12 object-contain border rounded p-1">
                                    </div>
                                @endif
                                <input type="file" name="logo" id="logo" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">Recommended size: 200x60px. Max 2MB.</p>
                            </div>
                            <div>
                                <label for="favicon" class="block text-sm font-medium text-gray-700 mb-1">Favicon</label>
                                @if ($faviconUrl)
                                    <div class="mb-2">
                                        <img src="{{ $faviconUrl }}" alt="Favicon"
                                            class="h-8 w-8 object-contain border rounded p-1">
                                    </div>
                                @endif
                                <input type="file" name="favicon" id="favicon"
                                    accept="image/x-icon,image/png,image/svg+xml"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">16x16px or 32x32px. ICO, PNG or SVG. Max 1MB.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer Tab --}}
                <div id="tab-footer" class="tab-panel hidden">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Footer</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-1">Footer
                                    Text</label>
                                <textarea name="footer_text" id="footer_text" rows="3"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('footer_text', $settings->footer_text) }}</textarea>
                            </div>
                            <div>
                                <label for="copyright_text" class="block text-sm font-medium text-gray-700 mb-1">Copyright
                                    Text</label>
                                <input type="text" name="copyright_text" id="copyright_text"
                                    value="{{ old('copyright_text', $settings->copyright_text) }}"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                <p class="mt-1 text-xs text-gray-500">Example: © 2024 AK Tech SOL. All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Social Links Tab --}}
                <div id="tab-social" class="tab-panel hidden">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Social Links</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1">Facebook
                                    URL</label>
                                <input type="url" name="facebook_url" id="facebook_url"
                                    value="{{ old('facebook_url', $settings->facebook_url) }}"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-1">Twitter
                                    URL</label>
                                <input type="url" name="twitter_url" id="twitter_url"
                                    value="{{ old('twitter_url', $settings->twitter_url) }}"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn
                                    URL</label>
                                <input type="url" name="linkedin_url" id="linkedin_url"
                                    value="{{ old('linkedin_url', $settings->linkedin_url) }}"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-1">Instagram
                                    URL</label>
                                <input type="url" name="instagram_url" id="instagram_url"
                                    value="{{ old('instagram_url', $settings->instagram_url) }}"
                                    class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="inline-flex justify-center items-center px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabPanels = document.querySelectorAll('.tab-panel');

            function switchTab(targetId) {
                // Hide all panels, show target
                tabPanels.forEach(panel => {
                    panel.classList.add('hidden');
                });
                const activePanel = document.getElementById('tab-' + targetId);
                if (activePanel) activePanel.classList.remove('hidden');

                // Deactivate all tab links, activate the selected one
                tabLinks.forEach(link => {
                    link.classList.remove('active', 'text-blue-600', 'border-blue-600');
                    link.classList.add('text-gray-500', 'border-transparent');
                });
                const activeLink = document.querySelector(`.tab-link[data-tab="${targetId}"]`);
                if (activeLink) {
                    activeLink.classList.add('active', 'text-blue-600', 'border-blue-600');
                    activeLink.classList.remove('text-gray-500', 'border-transparent');
                }
            }

            tabLinks.forEach(link => {
                link.addEventListener('click', function() {
                    const tab = this.getAttribute('data-tab');
                    switchTab(tab);
                });
            });
        })();
    </script>
@endpush
