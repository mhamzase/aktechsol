{{--
    Settings — tailor-app style tabbed one-page. No refresh: each tab saves
    via AJAX (data-ajax-form). Active tab persists in localStorage until the
    user arrives from another page (then the ?tab= query wins).
--}}
@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    <x-admin.page-header title="Settings"
                        subtitle="Configure your site preferences, notifications, and integrations."
                        icon="M13 10V3L4 14h7v7l9-11h-7z" />

    {{-- Tab bar (tailor-app style) --}}
    @php
        $tabIcons = [
            'general'  => 'M21 12a9 9 0 11-18 0 9 9 0 0118 0zM12 3v2m0 14v2m9-9h-2M5 12H3',
            'contact'  => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            'email'    => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
            'payments' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
            'popup'    => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.999 3.999 0 01-1.564-.317z',
            'homepage' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
            'seo'      => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
            'chatbot'  => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13a9 9 0 1118 0 9 9 0 01-18 0z',
        ];
    @endphp
    <div class="admin-card px-3 mb-5">
        <div class="flex gap-1.5 overflow-x-auto py-1" id="settings-tabs" role="tablist">
            @foreach ($tabs as $key => $label)
                <button type="button" role="tab" data-tab="{{ $key }}" class="tab-btn shrink-0">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tabIcons[$key] ?? $tabIcons['general'] }}"/></svg>
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    @php $s = $settings; @endphp

    {{-- GENERAL --}}
    <div data-tabpanel="general" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'general') }}" enctype="multipart/form-data" novalidate>
            @csrf @method('PUT')
            {{-- Site Information --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    Site Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <x-ui.input name="site_name" label="Site Name" :value="$s->site_name" required />
                    <x-ui.input name="site_email" type="email" label="Site Email" :value="$s->site_email" />
                    <x-ui.input name="site_phone" label="Phone" :value="$s->site_phone" placeholder="+92 300 0000000" />
                    <x-ui.input name="copyright_text" label="Copyright Text" :value="$s->copyright_text" />
                    <div class="sm:col-span-2 md:col-span-2"><x-ui.input name="site_address" label="Address" :value="$s->site_address" /></div>
                    <div class="sm:col-span-2 md:col-span-2"><x-ui.textarea name="footer_text" label="Footer Text" rows="1" :value="$s->footer_text" /></div>
                </div>
            </div>

            {{-- Social Media --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-violet">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    </span>
                    Social Media
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <x-ui.input name="facebook_url" type="url" label="Facebook URL" :value="$s->facebook_url" placeholder="https://" />
                    <x-ui.input name="instagram_url" type="url" label="Instagram URL" :value="$s->instagram_url" placeholder="https://" />
                    <x-ui.input name="linkedin_url" type="url" label="LinkedIn URL" :value="$s->linkedin_url" placeholder="https://" />
                    <x-ui.input name="twitter_url" type="url" label="Twitter URL" :value="$s->twitter_url" placeholder="https://" />
                </div>
            </div>

            {{-- Branding --}}
            <div class="mb-2">
                <h3 class="section-heading">
                    <span class="sec-icon sec-amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    Branding
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div data-brand-preview="logo">
                        @if ($logoUrl)<img src="{{ $logoUrl }}" class="h-10 mb-2 rounded-lg bg-slate-100 p-1 dark:bg-white/10" alt="Logo">@endif
                        <x-ui.file name="logo" label="Logo" accept="image/*" hint="PNG or SVG." />
                    </div>
                    <div data-brand-preview="favicon">
                        @if ($faviconUrl)<img src="{{ $faviconUrl }}" class="h-8 mb-2 rounded-lg bg-slate-100 p-1 dark:bg-white/10" alt="Favicon">@endif
                        <x-ui.file name="favicon" label="Favicon" accept="image/*" hint="Square, 32×32+." />
                    </div>
                </div>
            </div>
            <x-admin.drawer-footer save-label="Save General" />
        </form>
    </div>

    {{-- CONTACT & WHATSAPP --}}
    <div data-tabpanel="contact" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'contact') }}" novalidate>
            @csrf @method('PUT')
            {{-- WhatsApp --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </span>
                    WhatsApp
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2"><x-ui.input name="whatsapp_number" label="WhatsApp Number" :value="$s->whatsapp_number" placeholder="923001234567" hint="Country code + number, no + or spaces." /></div>
                    <div class="md:col-span-2">
                        <div class="toggle-box">
                            <div class="flex items-center gap-3">
                                <span class="tb-icon sec-green">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </span>
                                <div>
                                    <p class="tb-title">WhatsApp Float Button</p>
                                    <p class="tb-desc">Show floating WhatsApp button on website</p>
                                </div>
                            </div>
                            <x-ui.toggle name="whatsapp_enabled" :checked="$s->whatsapp_enabled" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notifications --}}
            <div class="mb-2">
                <h3 class="section-heading">
                    <span class="sec-icon sec-blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.003 2.003 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    </span>
                    Notifications
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2"><x-ui.input name="notification_email" type="email" label="Notification Email" :value="$s->notification_email" hint="Receives quote / contact / demo enquiries." /></div>
                </div>
            </div>
            <x-admin.drawer-footer save-label="Save Contact" />
        </form>
    </div>

    {{-- EMAIL (SMTP) --}}
    <div data-tabpanel="email" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'email') }}" novalidate>
            @csrf @method('PUT')
            {{-- SMTP Configuration --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    SMTP Configuration
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Leave host/username empty to keep using the server .env mail config.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <x-ui.input name="mail_host" label="SMTP Host" :value="$s->mail_host" placeholder="mail.aktechsol.pk" />
                    <x-ui.input name="mail_port" type="number" label="SMTP Port" :value="$s->mail_port" placeholder="587" />
                    <x-ui.input name="mail_username" label="SMTP Username" :value="$s->mail_username" />
                    <x-ui.password name="mail_password" label="SMTP Password" placeholder="{{ $s->mail_password ? '•••••• (saved — blank keeps)' : '' }}" autocomplete="new-password" />
                    <x-ui.select name="mail_encryption" label="Encryption" :value="$s->mail_encryption ?? 'tls'" :options="['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None']" />
                </div>
            </div>

            {{-- Sender --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-violet">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    Sender
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2"><x-ui.input name="mail_from_name" label="From Name" :value="$s->mail_from_name" placeholder="AK Tech Sol" /></div>
                    <div class="md:col-span-2"><x-ui.input name="mail_from_address" type="email" label="From Address" :value="$s->mail_from_address" placeholder="info@aktechsol.com" /></div>
                </div>
            </div>

            {{-- Test Email --}}
            <div class="mb-2">
                <h3 class="section-heading">
                    <span class="sec-icon sec-green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    Test Email
                </h3>
                <div class="flex flex-wrap gap-3 items-end">
                    <div class="w-80 max-w-full"><x-ui.input name="test_email_to" id="test-email-to" type="email" label="Recipient" placeholder="test@example.com" /></div>
                    <button type="button" class="btn btn-secondary shrink-0" onclick="sendTestEmail(this)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send Test Email
                    </button>
                </div>
            </div>
            <x-admin.drawer-footer save-label="Save Email Settings" />
        </form>
    </div>

    {{-- PAYMENTS --}}
    <div data-tabpanel="payments" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'payments') }}" novalidate>
            @csrf @method('PUT')
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </span>
                    Payment Methods
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Shown to clients on invoices (manual payments). Add each method with its account details.</p>
                <div id="payment-methods" class="space-y-4">
                    @forelse (($s->payment_methods ?? []) as $pm)
                        <div class="pm-card">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                <div class="ui-field"><label class="ui-label">Payment Method</label><div class="ui-input-wrap"><input name="payment_methods[][method]" class="ui-control" value="{{ $pm['method'] ?? $pm['name'] ?? '' }}" placeholder="Easypaisa / JazzCash / Bank Transfer"></div></div>
                                <div class="ui-field"><label class="ui-label">Account Holder Name</label><div class="ui-input-wrap"><input name="payment_methods[][account_holder]" class="ui-control" value="{{ $pm['account_holder'] ?? '' }}" placeholder="Muhammad Hamza"></div></div>
                                <div class="ui-field"><label class="ui-label">Bank Name</label><div class="ui-input-wrap"><input name="payment_methods[][bank_name]" class="ui-control" value="{{ $pm['bank_name'] ?? '' }}" placeholder="Meezan Bank"></div></div>
                                <div class="ui-field"><label class="ui-label">Account Number</label><div class="ui-input-wrap"><input name="payment_methods[][account_number]" class="ui-control" value="{{ $pm['account_number'] ?? '' }}" placeholder="0300 1234567"></div></div>
                                <div class="md:col-span-3 ui-field"><label class="ui-label">IBAN</label><div class="ui-input-wrap"><input name="payment_methods[][iban]" class="ui-control" value="{{ $pm['iban'] ?? '' }}" placeholder="PK36SCBL0000001123456702"></div></div>
                                <div class="flex items-end justify-end"><button type="button" class="btn btn-ghost !text-red-500" onclick="this.closest('.pm-card').remove()" title="Remove">✕ Remove</button></div>
                            </div>
                        </div>
                    @empty
                        <div class="pm-card">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                <div class="ui-field"><label class="ui-label">Payment Method</label><div class="ui-input-wrap"><input name="payment_methods[][method]" class="ui-control" placeholder="Easypaisa / JazzCash / Bank Transfer"></div></div>
                                <div class="ui-field"><label class="ui-label">Account Holder Name</label><div class="ui-input-wrap"><input name="payment_methods[][account_holder]" class="ui-control" placeholder="Muhammad Hamza"></div></div>
                                <div class="ui-field"><label class="ui-label">Bank Name</label><div class="ui-input-wrap"><input name="payment_methods[][bank_name]" class="ui-control" placeholder="Meezan Bank"></div></div>
                                <div class="ui-field"><label class="ui-label">Account Number</label><div class="ui-input-wrap"><input name="payment_methods[][account_number]" class="ui-control" placeholder="0300 1234567"></div></div>
                                <div class="md:col-span-3 ui-field"><label class="ui-label">IBAN</label><div class="ui-input-wrap"><input name="payment_methods[][iban]" class="ui-control" placeholder="PK36SCBL0000001123456702"></div></div>
                                <div class="flex items-end justify-end"><button type="button" class="btn btn-ghost !text-red-500" onclick="this.closest('.pm-card').remove()" title="Remove">✕ Remove</button></div>
                            </div>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-secondary mt-3" onclick="addPaymentRow()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    Add Method
                </button>
            </div>
            <x-admin.drawer-footer save-label="Save Payments" />
        </form>
    </div>

    {{-- POPUP --}}
    <div data-tabpanel="popup" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'popup') }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-600">
                        <input type="checkbox" name="popup_enabled" value="1" @checked($s->popup_enabled) class="w-4 h-4 accent-[#1d4ed8]">
                        Show announcement popup on website
                    </label>
                </div>
                <div><label class="form-label">Popup Title</label><input name="popup_title" class="form-control" value="{{ $s->popup_title }}"></div>
                <div><label class="form-label">Button Text</label><input name="popup_btn_text" class="form-control" value="{{ $s->popup_btn_text }}"></div>
                <div class="md:col-span-2"><label class="form-label">Message</label><textarea name="popup_message" rows="3" class="form-control">{{ $s->popup_message }}</textarea></div>
                <div class="md:col-span-2"><label class="form-label">Button URL</label><input name="popup_btn_url" class="form-control" value="{{ $s->popup_btn_url }}" placeholder="/contact"></div>
            </div>
            <div class="mt-5 flex justify-end"><button class="btn btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h9a2 2 0 002-2v-7m-5-5h5m0 0v5m0-5L8 16"/></svg> Save Popup</button></div>
        </form>
    </div>

    {{-- HOMEPAGE --}}
    <div data-tabpanel="homepage" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'homepage') }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2"><label class="form-label">Brand Message</label><input name="brand_message" class="form-control" value="{{ $s->brand_message }}" placeholder="AK Tech Sol is your complete digital solution partner"></div>
                <div><label class="form-label">Demo Video URL</label><input name="demo_video_url" class="form-control" value="{{ $s->demo_video_url }}" placeholder="https://youtube.com/..."></div>
                <div><label class="form-label">Demo Video Title</label><input name="demo_video_title" class="form-control" value="{{ $s->demo_video_title }}"></div>
                <div><label class="form-label">About — Hero Title</label><input name="about_hero_title" class="form-control" value="{{ $s->about_hero_title }}"></div>
                <div><label class="form-label">About — Intro Title</label><input name="about_intro_title" class="form-control" value="{{ $s->about_intro_title }}"></div>
                <div class="md:col-span-2"><label class="form-label">About — Hero Subtitle</label><textarea name="about_hero_subtitle" rows="2" class="form-control">{{ $s->about_hero_subtitle }}</textarea></div>
                <div class="md:col-span-2"><label class="form-label">About — Intro Text</label><textarea name="about_intro_text" rows="3" class="form-control">{{ $s->about_intro_text }}</textarea></div>
            </div>
            <div class="mt-5 flex justify-end"><button class="btn btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h9a2 2 0 002-2v-7m-5-5h5m0 0v5m0-5L8 16"/></svg> Save Homepage</button></div>
        </form>
    </div>

    {{-- SEO --}}
    <div data-tabpanel="seo" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'seo') }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="form-label">Meta Title</label><input name="seo_title" class="form-control" value="{{ $s->seo_title }}"></div>
                <div><label class="form-label">Meta Keywords</label><input name="seo_keywords" class="form-control" value="{{ $s->seo_keywords }}"></div>
                <div class="md:col-span-2"><label class="form-label">Meta Description</label><textarea name="seo_description" rows="2" class="form-control">{{ $s->seo_description }}</textarea></div>
                <div><label class="form-label">Canonical URL</label><input name="seo_canonical_url" class="form-control" value="{{ $s->seo_canonical_url }}"></div>
                <div><label class="form-label">Robots</label><input name="seo_robots" class="form-control" value="{{ $s->seo_robots }}" placeholder="index, follow"></div>
            </div>
            <div class="mt-5 flex justify-end"><button class="btn btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h9a2 2 0 002-2v-7m-5-5h5m0 0v5m0-5L8 16"/></svg> Save SEO</button></div>
        </form>
    </div>

    {{-- CHATBOT --}}
    <div data-tabpanel="chatbot" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'chatbot') }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-600">
                        <input type="checkbox" name="chatbot_enabled" value="1" @checked($s->chatbot_enabled) class="w-4 h-4 accent-[#1d4ed8]">
                        Enable chatbot widget on website
                    </label>
                </div>
                <div><label class="form-label">Team Name <span class="text-slate-400 font-normal">(widget header)</span></label><input name="chatbot_team_name" class="form-control" value="{{ $s->chatbot_team_name }}" placeholder="AK Tech Sol Team"></div>
                <div><label class="form-label">Greeting Message</label><input name="chatbot_greeting" class="form-control" value="{{ $s->chatbot_greeting }}" placeholder="Hi! How can we help you today?"></div>
            </div>
            <div class="mt-5 flex justify-end"><button class="btn btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h9a2 2 0 002-2v-7m-5-5h5m0 0v5m0-5L8 16"/></svg> Save Chatbot</button></div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
// Tab switching with localStorage persistence (tailor-app pattern)
(function () {
    const bar = document.getElementById('settings-tabs');
    const panels = document.querySelectorAll('[data-tabpanel]');
    const fromUrl = new URLSearchParams(location.search).get('tab');

    function show(key) {
        bar.querySelectorAll('[data-tab]').forEach(b => b.classList.toggle('active', b.dataset.tab === key));
        panels.forEach(p => p.classList.toggle('hidden', p.dataset.tabpanel !== key));
        localStorage.setItem('aktechsol_settings_tab', key);
    }
    bar.addEventListener('click', e => {
        const btn = e.target.closest('[data-tab]');
        if (btn) show(btn.dataset.tab);
    });
    show(fromUrl || localStorage.getItem('aktechsol_settings_tab') || @json($tab));
})();

function addPaymentRow() {
    const wrap = document.getElementById('payment-methods');
    const div = document.createElement('div');
    div.className = 'pm-card';
    div.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <div class="ui-field"><label class="ui-label">Payment Method</label><div class="ui-input-wrap"><input name="payment_methods[][method]" class="ui-control" placeholder="Easypaisa / JazzCash / Bank Transfer"></div></div>
        <div class="ui-field"><label class="ui-label">Account Holder Name</label><div class="ui-input-wrap"><input name="payment_methods[][account_holder]" class="ui-control" placeholder="Muhammad Hamza"></div></div>
        <div class="ui-field"><label class="ui-label">Bank Name</label><div class="ui-input-wrap"><input name="payment_methods[][bank_name]" class="ui-control" placeholder="Meezan Bank"></div></div>
        <div class="ui-field"><label class="ui-label">Account Number</label><div class="ui-input-wrap"><input name="payment_methods[][account_number]" class="ui-control" placeholder="0300 1234567"></div></div>
        <div class="md:col-span-3 ui-field"><label class="ui-label">IBAN</label><div class="ui-input-wrap"><input name="payment_methods[][iban]" class="ui-control" placeholder="PK36SCBL0000001123456702"></div></div>
        <div class="flex items-end justify-end"><button type="button" class="btn btn-ghost !text-red-500" onclick="this.closest('.pm-card').remove()">✕ Remove</button></div>
    </div>`;
    wrap.appendChild(div);
}

async function sendTestEmail(btn) {
    const input = document.getElementById('test-email-to');
    const to = (input?.value || '').trim();
    if (!to) {
        window.notify.error('Enter a recipient email first.');
        input?.focus();
        return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(to)) {
        window.notify.error('Please enter a valid email address.');
        input?.focus();
        return;
    }
    btn = btn || document.querySelector('[onclick^="sendTestEmail"]');
    const original = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.innerHTML = '<span class="spin inline-block">◌</span> Sending…'; }
    try {
        const res = await fetch(@json(route('admin.settings.test-email')), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, Accept: 'application/json' },
            body: JSON.stringify({ email: to }),
        });
        const data = await res.json().catch(() => ({}));
        res.ok ? window.notify.success(data.message || 'Test email sent.') : window.notify.error(data.message || `Failed (HTTP ${res.status}).`);
    } catch (e) { window.notify.error('Request failed. Check your connection.'); }
    finally { if (btn) { btn.disabled = false; btn.innerHTML = original; } }
}
</script>
@endpush
