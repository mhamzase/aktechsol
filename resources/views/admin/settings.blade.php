@extends('admin.layouts.app')

@section('title', 'Site Settings')

@section('breadcrumbs')
    <x-admin.breadcrumbs :items="[['label' => 'Dashboard', 'url' => route('admin.dashboard')], ['label' => 'Settings']]" />
@endsection

@section('content')
    <x-admin.page-banner title="Site Settings" subtitle="Manage your website configuration" stats="5 configurable tabs" />

    <div class="w-full mx-auto">
        @if ($errors->any())
            <x-admin.alert type="error" :messages="$errors->all()" />
        @endif

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="flex flex-wrap -mb-px gap-1" id="settingsTabs" role="tablist">
                    <button type="button" class="tab-link active inline-flex items-center px-4 py-3 text-sm font-medium text-blue-600 border-b-2 border-blue-600" data-tab="general">General</button>
                    <button type="button" class="tab-link inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300" data-tab="branding">Branding</button>
                    <button type="button" class="tab-link inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300" data-tab="footer">Footer</button>
                    <button type="button" class="tab-link inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300" data-tab="social">Social Links</button>
                    <button type="button" class="tab-link inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300" data-tab="about">About Page</button>
                    <button type="button" class="tab-link inline-flex items-center px-4 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300" data-tab="legal">Legal Pages</button>
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
                                <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">Website Name</label>
                                <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $settings->site_name) }}" required class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('site_name') border-red-500 @enderror">
                                @error('site_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="site_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="site_email" id="site_email" value="{{ old('site_email', $settings->site_email) }}" required class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none @error('site_email') border-red-500 @enderror">
                                @error('site_email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="site_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" name="site_phone" id="site_phone" value="{{ old('site_phone', $settings->site_phone) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div class="md:col-span-2">
                                <label for="site_address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea name="site_address" id="site_address" rows="2" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('site_address', $settings->site_address) }}</textarea>
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
                                    <div class="mb-2"><img src="{{ $logoUrl }}" alt="Logo" class="h-12 object-contain border rounded p-1"></div>
                                @endif
                                <input type="file" name="logo" id="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1 text-xs text-gray-500">Recommended size: 200x60px. Max 2MB.</p>
                            </div>
                            <div>
                                <label for="favicon" class="block text-sm font-medium text-gray-700 mb-1">Favicon</label>
                                @if ($faviconUrl)
                                    <div class="mb-2"><img src="{{ $faviconUrl }}" alt="Favicon" class="h-8 w-8 object-contain border rounded p-1"></div>
                                @endif
                                <input type="file" name="favicon" id="favicon" accept="image/x-icon,image/png,image/svg+xml" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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
                                <label for="footer_text" class="block text-sm font-medium text-gray-700 mb-1">Footer Text</label>
                                <textarea name="footer_text" id="footer_text" rows="3" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('footer_text', $settings->footer_text) }}</textarea>
                            </div>
                            <div>
                                <label for="copyright_text" class="block text-sm font-medium text-gray-700 mb-1">Copyright Text</label>
                                <input type="text" name="copyright_text" id="copyright_text" value="{{ old('copyright_text', $settings->copyright_text) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
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
                                <label for="facebook_url" class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                                <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="twitter_url" class="block text-sm font-medium text-gray-700 mb-1">Twitter URL</label>
                                <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                                <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label for="instagram_url" class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                                <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- About Page Tab --}}
                <div id="tab-about" class="tab-panel hidden">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 space-y-5">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">About Page Content</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="about_hero_title" class="block text-sm font-medium text-gray-700 mb-1">Hero Title</label>
                                <input type="text" name="about_hero_title" id="about_hero_title" value="{{ old('about_hero_title', $settings->about_hero_title) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="about_hero_subtitle" class="block text-sm font-medium text-gray-700 mb-1">Hero Subtitle</label>
                                <textarea name="about_hero_subtitle" id="about_hero_subtitle" rows="3" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('about_hero_subtitle', $settings->about_hero_subtitle) }}</textarea>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="about_intro_title" class="block text-sm font-medium text-gray-700 mb-1">Intro Title</label>
                                <input type="text" name="about_intro_title" id="about_intro_title" value="{{ old('about_intro_title', $settings->about_intro_title) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="about_intro_text" class="block text-sm font-medium text-gray-700 mb-1">Intro Text</label>
                                <textarea name="about_intro_text" id="about_intro_text" rows="4" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('about_intro_text', $settings->about_intro_text) }}</textarea>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="about_mission_title" class="block text-sm font-medium text-gray-700 mb-1">Mission Title</label>
                                <input type="text" name="about_mission_title" id="about_mission_title" value="{{ old('about_mission_title', $settings->about_mission_title) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="about_mission_subtitle" class="block text-sm font-medium text-gray-700 mb-1">Mission Subtitle</label>
                                <input type="text" name="about_mission_subtitle" id="about_mission_subtitle" value="{{ old('about_mission_subtitle', $settings->about_mission_subtitle) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                        @for ($i = 1; $i <= 3; $i++)
                        <div class="border-t border-gray-100 pt-4">
                            <h4 class="text-sm font-semibold text-gray-800 mb-2">Mission Card {{ $i }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                    <input type="text" name="about_mission_card{{ $i }}_title" value="{{ old("about_mission_card{$i}_title", $settings->{"about_mission_card{$i}_title"}) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                                    <textarea name="about_mission_card{{ $i }}_text" rows="3" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old("about_mission_card{$i}_text", $settings->{"about_mission_card{$i}_text"}) }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endfor
                        <div>
                            <label for="about_why_title" class="block text-sm font-medium text-gray-700 mb-1">Why Choose Us Title</label>
                            <input type="text" name="about_why_title" id="about_why_title" value="{{ old('about_why_title', $settings->about_why_title) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @for ($i = 1; $i <= 4; $i++)
                        <div class="border-t border-gray-100 pt-4">
                            <h4 class="text-sm font-semibold text-gray-800 mb-2">Why Choose Item {{ $i }}</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                                    <input type="text" name="about_why_item{{ $i }}_title" value="{{ old("about_why_item{$i}_title", $settings->{"about_why_item{$i}_title"}) }}" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Text</label>
                                    <textarea name="about_why_item{{ $i }}_text" rows="3" class="block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old("about_why_item{$i}_text", $settings->{"about_why_item{$i}_text"}) }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                {{-- Legal Pages Tab --}}
                <div id="tab-legal" class="tab-panel hidden">
                    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Legal Pages Content</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Privacy Policy</label>
                                <input type="hidden" name="privacy_policy_content" id="privacy_policy_content" value="{{ old('privacy_policy_content', $settings->privacy_policy_content ?? '') }}">
                                <div id="privacy-editor" style="height: 300px;"></div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Terms & Conditions</label>
                                <input type="hidden" name="terms_conditions_content" id="terms_conditions_content" value="{{ old('terms_conditions_content', $settings->terms_conditions_content ?? '') }}">
                                <div id="terms-editor" style="height: 300px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Save Button --}}
            <div class="flex justify-end mt-6">
                <button type="submit" class="inline-flex justify-center items-center px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    Save Settings
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/quill/quill.snow.css') }}">
    <style>
        .ql-editor { min-height: 250px; }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/quill/quill.js') }}"></script>
    <script>
        (function() {
            // Tab switching
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabPanels = document.querySelectorAll('.tab-panel');

            function switchTab(targetId) {
                tabPanels.forEach(panel => panel.classList.add('hidden'));
                const activePanel = document.getElementById('tab-' + targetId);
                if (activePanel) activePanel.classList.remove('hidden');

                tabLinks.forEach(link => {
                    link.classList.remove('active', 'text-blue-600', 'border-blue-600');
                    link.classList.add('text-gray-500', 'border-transparent');
                });
                const activeLink = document.querySelector(`.tab-link[data-tab="${targetId}"]`);
                if (activeLink) {
                    activeLink.classList.add('active', 'text-blue-600', 'border-blue-600');
                    activeLink.classList.remove('text-gray-500', 'border-transparent');
                }

                // Initialize Quill editors when legal tab is opened
                if (targetId === 'legal' && !window.legalEditorsInitialized) {
                    initLegalEditors();
                    window.legalEditorsInitialized = true;
                }
            }

            tabLinks.forEach(link => {
                link.addEventListener('click', function() {
                    switchTab(this.getAttribute('data-tab'));
                });
            });

            // Quill initialization for legal pages
            function initLegalEditors() {
                var privacyHidden = document.getElementById('privacy_policy_content');
                var privacyEditor = document.getElementById('privacy-editor');
                if (privacyHidden && privacyEditor) {
                    var quillPrivacy = new Quill(privacyEditor, {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                ['link'],
                                ['clean']
                            ]
                        }
                    });
                    quillPrivacy.root.innerHTML = privacyHidden.value;
                    var form = privacyHidden.closest('form');
                    if (form) {
                        form.addEventListener('submit', function() {
                            privacyHidden.value = quillPrivacy.root.innerHTML;
                        });
                    }
                }

                var termsHidden = document.getElementById('terms_conditions_content');
                var termsEditor = document.getElementById('terms-editor');
                if (termsHidden && termsEditor) {
                    var quillTerms = new Quill(termsEditor, {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                ['link'],
                                ['clean']
                            ]
                        }
                    });
                    quillTerms.root.innerHTML = termsHidden.value;
                    var termsForm = termsHidden.closest('form');
                    if (termsForm) {
                        termsForm.addEventListener('submit', function() {
                            termsHidden.value = quillTerms.root.innerHTML;
                        });
                    }
                }
            }

            // If the legal tab is active on load (e.g., after validation error), initialize immediately
            if (document.querySelector('.tab-link[data-tab="legal"].active')) {
                initLegalEditors();
                window.legalEditorsInitialized = true;
            }
        })();
    </script>
@endpush
