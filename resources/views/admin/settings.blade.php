{{--
    Settings — tailor-app style tabbed one-page. No refresh: each tab saves
    via AJAX (data-ajax-form). Active tab persists in localStorage until the
    user arrives from another page (then the ?tab= query wins).
--}}
@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    <x-admin.page-header title="Settings" subtitle="Site-wide configuration" />

    {{-- Tab bar --}}
    <div class="flex gap-2 overflow-x-auto pb-2 mb-5" id="settings-tabs" role="tablist">
        @foreach ($tabs as $key => $label)
            <button type="button" role="tab" data-tab="{{ $key }}"
                    class="tab-btn shrink-0 px-4 py-2 rounded-lg text-sm font-semibold text-slate-600 bg-white border border-slate-200 transition-colors">
                {{ $label }}
            </button>
        @endforeach
    </div>

    @php $s = $settings; @endphp

    {{-- GENERAL --}}
    <div data-tabpanel="general" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'general') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="form-label">Site Name</label><input name="site_name" class="form-control" value="{{ $s->site_name }}"></div>
                <div><label class="form-label">Site Email</label><input name="site_email" type="email" class="form-control" value="{{ $s->site_email }}"></div>
                <div><label class="form-label">Phone</label><input name="site_phone" class="form-control" value="{{ $s->site_phone }}"></div>
                <div><label class="form-label">Address</label><input name="site_address" class="form-control" value="{{ $s->site_address }}"></div>
                <div class="md:col-span-2"><label class="form-label">Footer Text</label><textarea name="footer_text" rows="2" class="form-control">{{ $s->footer_text }}</textarea></div>
                <div><label class="form-label">Copyright Text</label><input name="copyright_text" class="form-control" value="{{ $s->copyright_text }}"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Facebook URL</label><input name="facebook_url" class="form-control" value="{{ $s->facebook_url }}"></div>
                    <div><label class="form-label">Instagram URL</label><input name="instagram_url" class="form-control" value="{{ $s->instagram_url }}"></div>
                    <div><label class="form-label">LinkedIn URL</label><input name="linkedin_url" class="form-control" value="{{ $s->linkedin_url }}"></div>
                    <div><label class="form-label">Twitter URL</label><input name="twitter_url" class="form-control" value="{{ $s->twitter_url }}"></div>
                </div>
                <div>
                    <label class="form-label">Logo</label>
                    @if ($logoUrl)<img src="{{ $logoUrl }}" class="h-12 mb-2 rounded bg-slate-100 p-1" alt="Logo">@endif
                    <input type="file" name="logo" accept="image/*" class="form-control">
                </div>
                <div>
                    <label class="form-label">Favicon</label>
                    @if ($faviconUrl)<img src="{{ $faviconUrl }}" class="h-8 mb-2 rounded bg-slate-100 p-1" alt="Favicon">@endif
                    <input type="file" name="favicon" accept="image/*" class="form-control">
                </div>
            </div>
            <div class="mt-5"><button class="btn btn-primary">Save General</button></div>
        </form>
    </div>

    {{-- CONTACT & WHATSAPP --}}
    <div data-tabpanel="contact" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'contact') }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-label">WhatsApp Number <span class="text-slate-400 font-normal">(e.g. 923001234567)</span></label>
                    <input name="whatsapp_number" class="form-control" value="{{ $s->whatsapp_number }}">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-600">
                        <input type="checkbox" name="whatsapp_enabled" value="1" @checked($s->whatsapp_enabled) class="w-4 h-4 accent-[#1d4ed8]">
                        Show WhatsApp float button on website
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="form-label">Notification Email <span class="text-slate-400 font-normal">(receives quote / contact / demo enquiries)</span></label>
                    <input name="notification_email" type="email" class="form-control" value="{{ $s->notification_email }}">
                </div>
            </div>
            <div class="mt-5"><button class="btn btn-primary">Save Contact</button></div>
        </form>
    </div>

    {{-- EMAIL (SMTP) --}}
    <div data-tabpanel="email" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'email') }}">
            @csrf @method('PUT')
            <p class="text-xs text-slate-400 mb-4">Leave host/username empty to keep using the server .env mail config.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="form-label">SMTP Host</label><input name="mail_host" class="form-control" value="{{ $s->mail_host }}" placeholder="mail.aktechsol.pk"></div>
                <div><label class="form-label">SMTP Port</label><input name="mail_port" type="number" class="form-control" value="{{ $s->mail_port }}"></div>
                <div><label class="form-label">SMTP Username</label><input name="mail_username" class="form-control" value="{{ $s->mail_username }}"></div>
                <div><label class="form-label">SMTP Password</label><input name="mail_password" type="password" class="form-control" placeholder="{{ $s->mail_password ? '•••••• (saved — leave blank to keep)' : '' }}" autocomplete="new-password"></div>
                <div>
                    <label class="form-label">Encryption</label>
                    <select name="mail_encryption" class="form-control">
                        <option value="tls" @selected($s->mail_encryption === 'tls')>TLS</option>
                        <option value="ssl" @selected($s->mail_encryption === 'ssl')>SSL</option>
                        <option value="none" @selected($s->mail_encryption === 'none')>None</option>
                    </select>
                </div>
                <div><label class="form-label">From Name</label><input name="mail_from_name" class="form-control" value="{{ $s->mail_from_name }}"></div>
                <div class="md:col-span-2"><label class="form-label">From Address</label><input name="mail_from_address" type="email" class="form-control" value="{{ $s->mail_from_address }}"></div>
            </div>
            <div class="mt-5 flex flex-wrap items-center gap-3">
                <button class="btn btn-primary">Save Email Settings</button>
                <div class="flex items-center gap-2">
                    <input id="test-email-to" type="email" placeholder="Test recipient email" class="form-control !w-64">
                    <button type="button" class="btn btn-secondary" onclick="sendTestEmail()">Send Test Email</button>
                </div>
            </div>
        </form>
    </div>

    {{-- PAYMENTS --}}
    <div data-tabpanel="payments" class="admin-card p-5 hidden">
        <form data-ajax-form method="POST" action="{{ route('admin.settings.update', 'payments') }}">
            @csrf @method('PUT')
            <p class="text-xs text-slate-400 mb-4">Payment methods shown to clients on invoices (manual payments).</p>
            <div id="payment-methods" class="space-y-3">
                @forelse (($s->payment_methods ?? []) as $pm)
                    <div class="grid grid-cols-1 md:grid-cols-[1fr_2fr_auto] gap-3 items-end">
                        <div><label class="form-label">Name</label><input name="payment_methods[][name]" class="form-control" value="{{ $pm['name'] ?? '' }}" placeholder="JazzCash"></div>
                        <div><label class="form-label">Details</label><input name="payment_methods[][details]" class="form-control" value="{{ $pm['details'] ?? '' }}" placeholder="Account title / number"></div>
                        <button type="button" class="btn btn-ghost" onclick="this.parentElement.remove()">✕</button>
                    </div>
                @empty
                    <div class="grid grid-cols-1 md:grid-cols-[1fr_2fr_auto] gap-3 items-end">
                        <div><label class="form-label">Name</label><input name="payment_methods[][name]" class="form-control" placeholder="JazzCash"></div>
                        <div><label class="form-label">Details</label><input name="payment_methods[][details]" class="form-control" placeholder="Account title / number"></div>
                        <button type="button" class="btn btn-ghost" onclick="this.parentElement.remove()">✕</button>
                    </div>
                @endforelse
            </div>
            <button type="button" class="btn btn-secondary mt-3" onclick="addPaymentRow()">+ Add Method</button>
            <div class="mt-5"><button class="btn btn-primary">Save Payments</button></div>
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
            <div class="mt-5"><button class="btn btn-primary">Save Popup</button></div>
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
            <div class="mt-5"><button class="btn btn-primary">Save Homepage</button></div>
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
            <div class="mt-5"><button class="btn btn-primary">Save SEO</button></div>
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
            <div class="mt-5"><button class="btn btn-primary">Save Chatbot</button></div>
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
    div.className = 'grid grid-cols-1 md:grid-cols-[1fr_2fr_auto] gap-3 items-end';
    div.innerHTML = `<div><label class="form-label">Name</label><input name="payment_methods[][name]" class="form-control" placeholder="JazzCash"></div>
        <div><label class="form-label">Details</label><input name="payment_methods[][details]" class="form-control" placeholder="Account title / number"></div>
        <button type="button" class="btn btn-ghost" onclick="this.parentElement.remove()">✕</button>`;
    wrap.appendChild(div);
}

async function sendTestEmail() {
    const to = document.getElementById('test-email-to').value.trim();
    if (!to) return window.notify.error('Enter a recipient email first.');
    try {
        const res = await fetch(@json(route('admin.settings.test-email')), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, Accept: 'application/json' },
            body: JSON.stringify({ email: to }),
        });
        const data = await res.json();
        res.ok ? window.notify.success(data.message) : window.notify.error(data.message);
    } catch (e) { window.notify.error('Request failed.'); }
}
</script>
@endpush
