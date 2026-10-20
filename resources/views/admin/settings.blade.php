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
    <div class="admin-card pl-1 pr-3 mb-5">
        <div class="grid grid-cols-2 sm:flex gap-1.5 sm:overflow-x-auto py-1" id="settings-tabs" role="tablist">
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
                    <div class="sm:col-span-2 md:col-span-4"><x-ui.input name="google_map_embed_url" label="Google Map Embed URL" :value="$s->google_map_embed_url" placeholder="https://www.google.com/maps/embed?pb=..." hint="Google Maps kholen → address search karen → Share → Embed a map → <iframe> wala code copy karen → sirf src= ke andar wala URL yahan paste karen." /></div>
                    <div class="sm:col-span-2 md:col-span-2"><x-ui.textarea name="footer_text" label="Footer Text" rows="1" :value="$s->footer_text" /></div>
                </div>
            </div>

            {{-- Homepage Stats --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </span>
                    Homepage Stats
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    <x-ui.input name="stat1_label" label="Stat 1 — Label" :value="$s->stat1_label" placeholder="Projects Done" />
                    <x-ui.input name="stat1_value" label="Stat 1 — Value" :value="$s->stat1_value" placeholder="50+" />
                    <div class="hidden md:block"></div>
                    <x-ui.input name="stat2_label" label="Stat 2 — Label" :value="$s->stat2_label" placeholder="Countries Served" />
                    <x-ui.input name="stat2_value" label="Stat 2 — Value" :value="$s->stat2_value" placeholder="20+" />
                    <div class="hidden md:block"></div>
                    <x-ui.input name="stat3_label" label="Stat 3 — Label" :value="$s->stat3_label" placeholder="Happy Clients" />
                    <x-ui.input name="stat3_value" label="Stat 3 — Value" :value="$s->stat3_value" placeholder="100+" />
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
                    <div style="margin-bottom: 1rem;">
                        <button type="button" class="btn btn-secondary shrink-0" onclick="sendTestEmail(this)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Send Test Email
                        </button>
                    </div>
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
                <div class="grid md:grid-cols-12 gap-4 mb-5">
                    <div class="md:col-span-4">
                        <x-ui.select name="default_currency" label="Default Currency" :value="$s->default_currency ?? 'PKR'"
                                     :options="\App\Support\Money::options(true)"
                                     hint="Pre-selected on new proposals, projects and invoices. Each deal keeps its own currency." />
                    </div>
                </div>
                <div id="payment-methods" class="space-y-4">
                    @forelse (($s->payment_methods ?? []) as $idx => $pm)
                        <div class="pm-card" data-pm-index="{{ $idx }}">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                                <div class="ui-field"><label class="ui-label">Payment Method</label><div class="ui-input-wrap"><input name="payment_methods[{{ $idx }}][method]" class="ui-control" value="{{ $pm['method'] ?? $pm['name'] ?? '' }}" placeholder="Easypaisa / JazzCash / Bank Transfer"></div></div>
                                <div class="ui-field"><label class="ui-label">Account Holder Name</label><div class="ui-input-wrap"><input name="payment_methods[{{ $idx }}][account_holder]" class="ui-control" value="{{ $pm['account_holder'] ?? '' }}" placeholder="Muhammad Hamza"></div></div>
                                <div class="ui-field"><label class="ui-label">Bank Name</label><div class="ui-input-wrap"><input name="payment_methods[{{ $idx }}][bank_name]" class="ui-control" value="{{ $pm['bank_name'] ?? '' }}" placeholder="Meezan Bank"></div></div>
                                <div class="ui-field"><label class="ui-label">Account Number</label><div class="ui-input-wrap"><input name="payment_methods[{{ $idx }}][account_number]" class="ui-control" value="{{ $pm['account_number'] ?? '' }}" placeholder="0300 1234567"></div></div>
                                <div class="md:col-span-3 ui-field"><label class="ui-label">IBAN</label><div class="ui-input-wrap"><input name="payment_methods[{{ $idx }}][iban]" class="ui-control" value="{{ $pm['iban'] ?? '' }}" placeholder="PK36SCBL0000001123456702"></div></div>
                                <div class="flex items-end justify-end"><button type="button" class="btn btn-ghost !text-red-500" onclick="removePaymentRow(this)" title="Remove" class="btn btn-ghost !text-red-500 pm-remove">✕ Remove</button></div>
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
                                <div class="flex items-end justify-end"><button type="button" class="btn btn-ghost !text-red-500" onclick="removePaymentRow(this)" title="Remove" class="btn btn-ghost !text-red-500 pm-remove">✕ Remove</button></div>
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
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'popup') }}" novalidate enctype="multipart/form-data">
            @csrf @method('PUT')
            {{-- Visibility --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-violet">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    </span>
                    Visibility
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2">
                        <div class="toggle-box">
                            <div class="flex items-center gap-3">
                                <span class="tb-icon sec-violet">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.999 3.999 0 01-1.564-.317z"/></svg>
                                </span>
                                <div>
                                    <p class="tb-title">Announcement Popup</p>
                                    <p class="tb-desc">Show popup on website</p>
                                </div>
                            </div>
                            <x-ui.toggle name="popup_enabled" :checked="$s->popup_enabled" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="mb-2">
                <h3 class="section-heading">
                    <span class="sec-icon sec-blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    Content
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3">
                    <div class="md:col-span-6"><x-ui.input name="popup_link_url" type="url" label="Link URL (optional)" :value="$s->popup_link_url" placeholder="https://… or /get-a-quote" hint="Where visitors go when they click the popup image." /></div>
                    <div class="md:col-span-6" data-brand-preview="popup_image">
                        @if ($popupImageUrl)<img src="{{ $popupImageUrl }}" class="h-16 mb-2 rounded-lg bg-slate-100 p-1 dark:bg-white/10" alt="Popup image">@endif
                        <x-ui.file name="popup_image" label="Popup Image" accept="image/jpeg,image/jpg,image/png,image/webp" hint="The announcement image. JPG/PNG/WebP, max 2MB." />
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-3">Visitors see a "Don't show me again" option. Changing the image resets it for everyone.</p>
            </div>
            <x-admin.drawer-footer save-label="Save Popup" />
        </form>
    </div>

    {{-- HOMEPAGE --}}
    <div data-tabpanel="homepage" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'homepage') }}" novalidate>
            @csrf @method('PUT')
            {{-- Brand --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </span>
                    Brand
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div class="md:col-span-4"><x-ui.input name="brand_message" label="Brand Message" :value="$s->brand_message" placeholder="AK Tech Sol is your complete digital solution partner" /></div>
                </div>
            </div>

            {{-- Demo Video --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-violet">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    Demo Video
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2"><x-ui.input name="demo_video_url" type="url" label="Demo Video URL" :value="$s->demo_video_url" placeholder="https://youtube.com/…" /></div>
                    <div class="md:col-span-2"><x-ui.input name="demo_video_title" label="Demo Video Title" :value="$s->demo_video_title" placeholder="Watch our story" /></div>
                </div>
            </div>

            {{-- About Section --}}
            <div class="mb-2">
                <h3 class="section-heading">
                    <span class="sec-icon sec-amber">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    About Section
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2"><x-ui.input name="about_hero_title" label="Hero Title" :value="$s->about_hero_title" placeholder="About Us" /></div>
                    <div class="md:col-span-2"><x-ui.input name="about_intro_title" label="Intro Title" :value="$s->about_intro_title" placeholder="Who We Are" /></div>
                    <div class="md:col-span-2"><x-ui.textarea name="about_hero_subtitle" label="Hero Subtitle" rows="2" :value="$s->about_hero_subtitle" /></div>
                    <div class="md:col-span-2"><x-ui.textarea name="about_intro_text" label="Intro Text" rows="2" :value="$s->about_intro_text" /></div>
                    <div class="md:col-span-2"><x-ui.input name="about_mission_title" label="Mission Title" :value="$s->about_mission_title" placeholder="Our Mission & Values" /></div>
                    <div class="md:col-span-2"><x-ui.textarea name="about_mission_subtitle" label="Mission Subtitle" rows="2" :value="$s->about_mission_subtitle" /></div>
                </div>
            </div>
            <x-admin.drawer-footer save-label="Save Homepage" />
        </form>
    </div>

    {{-- SEO --}}
    <div data-tabpanel="seo" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'seo') }}" novalidate enctype="multipart/form-data">
            @csrf @method('PUT')
            {{-- Meta Tags --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </span>
                    Meta Tags
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2"><x-ui.input name="seo_title" label="Meta Title" :value="$s->seo_title" placeholder="AK Tech Sol — Digital Agency" /></div>
                    <div class="md:col-span-2"><x-ui.input name="seo_keywords" label="Meta Keywords" :value="$s->seo_keywords" placeholder="web development, design, …" /></div>
                    <div class="md:col-span-4"><x-ui.textarea name="seo_description" label="Meta Description" rows="2" :value="$s->seo_description" placeholder="Short description for search engines…" /></div>
                </div>
            </div>

            {{-- Indexing & Social --}}
            <div class="mb-2">
                <h3 class="section-heading">
                    <span class="sec-icon sec-green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    Indexing & Social
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2"><x-ui.input name="seo_canonical_url" type="url" label="Canonical URL" :value="$s->seo_canonical_url" placeholder="https://aktechsol.pk" /></div>
                    <div class="md:col-span-2"><x-ui.input name="seo_robots" label="Robots" :value="$s->seo_robots" placeholder="index, follow" /></div>
                    <div class="md:col-span-2" data-brand-preview="seo_og_image">
                        @if ($seoOgImageUrl)<img src="{{ $seoOgImageUrl }}" class="h-16 mb-2 rounded-lg bg-slate-100 p-1 dark:bg-white/10" alt="OG image">@endif
                        <x-ui.file name="seo_og_image" label="OG Image (optional)" accept="image/jpeg,image/jpg,image/png,image/webp" hint="Shown when sharing links. JPG/PNG/WebP, max 2MB." />
                    </div>
                </div>
            </div>
            <x-admin.drawer-footer save-label="Save SEO" />
        </form>
    </div>

    {{-- CHATBOT --}}
    <div data-tabpanel="chatbot" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'chatbot') }}" novalidate>
            @csrf @method('PUT')
            {{-- Widget --}}
            <div class="mb-5">
                <h3 class="section-heading">
                    <span class="sec-icon sec-blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </span>
                    Widget
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2">
                        <div class="toggle-box">
                            <div class="flex items-center gap-3">
                                <span class="tb-icon sec-blue">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                </span>
                                <div>
                                    <p class="tb-title">Chatbot Widget</p>
                                    <p class="tb-desc">Show on website</p>
                                </div>
                            </div>
                            <x-ui.toggle name="chatbot_enabled" :checked="$s->chatbot_enabled" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Appearance --}}
            <div class="mb-2">
                <h3 class="section-heading">
                    <span class="sec-icon sec-violet">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </span>
                    Appearance
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="md:col-span-2"><x-ui.input name="chatbot_team_name" label="Team Name" :value="$s->chatbot_team_name" placeholder="AK Tech Sol Team" hint="Shown in the widget header." /></div>
                    <div class="md:col-span-2"><x-ui.input name="chatbot_greeting" label="Greeting Message" :value="$s->chatbot_greeting" placeholder="Hi! How can we help you today?" /></div>
                </div>
            </div>
            <x-admin.drawer-footer save-label="Save Chatbot" />
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
    const idx = wrap.querySelectorAll('.pm-card').length;
    const div = document.createElement('div');
    div.className = 'pm-card';
    div.dataset.pmIndex = idx;
    div.innerHTML = `<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
        <div class="ui-field"><label class="ui-label">Payment Method</label><div class="ui-input-wrap"><input name="payment_methods[${idx}][method]" class="ui-control" placeholder="Easypaisa / JazzCash / Bank Transfer"></div></div>
        <div class="ui-field"><label class="ui-label">Account Holder Name</label><div class="ui-input-wrap"><input name="payment_methods[${idx}][account_holder]" class="ui-control" placeholder="Muhammad Hamza"></div></div>
        <div class="ui-field"><label class="ui-label">Bank Name</label><div class="ui-input-wrap"><input name="payment_methods[${idx}][bank_name]" class="ui-control" placeholder="Meezan Bank"></div></div>
        <div class="ui-field"><label class="ui-label">Account Number</label><div class="ui-input-wrap"><input name="payment_methods[${idx}][account_number]" class="ui-control" placeholder="0300 1234567"></div></div>
        <div class="md:col-span-3 ui-field"><label class="ui-label">IBAN</label><div class="ui-input-wrap"><input name="payment_methods[${idx}][iban]" class="ui-control" placeholder="PK36SCBL0000001123456702"></div></div>
        <div class="flex items-end justify-end"><button type="button" class="btn btn-ghost !text-red-500 pm-remove" onclick="removePaymentRow(this)">✕ Remove</button></div>
    </div>`;
    wrap.appendChild(div);
    refreshPaymentRemoveButtons();
}
function removePaymentRow(btn) {
    const wrap = document.getElementById('payment-methods');
    const cards = wrap.querySelectorAll('.pm-card');
    if (cards.length <= 1) {
        window.notify?.error('At least one payment method is required.');
        return;
    }
    btn.closest('.pm-card').remove();
    refreshPaymentRemoveButtons();
}
function refreshPaymentRemoveButtons() {
    const wrap = document.getElementById('payment-methods');
    if (!wrap) return;
    const cards = wrap.querySelectorAll('.pm-card');
    cards.forEach((card) => {
        const btn = card.querySelector('.pm-remove');
        if (btn) btn.style.display = cards.length > 1 ? '' : 'none';
    });
}
// Init on page load
document.addEventListener('DOMContentLoaded', refreshPaymentRemoveButtons);

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
