@extends('layouts.app')

@section('title', 'Contact Us | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Get in touch with ' . ($siteSettings->site_name ?? 'AK Tech SOL') . ' — we respond within 24 hours.')

@section('content')
    <x-frontend.page-banner
        title="Contact Us"
        subtitle="Have a project in mind? We'd love to hear from you."
    />

    <section class="py-16 bg-slate-50/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div id="contact-success" class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm hidden"></div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                {{-- Contact Details --}}
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-br from-[#0a1f44] to-[#1d4ed8] rounded-3xl p-8 text-white">
                        <h2 class="text-2xl font-extrabold">Get in Touch</h2>
                        <p class="text-blue-100/70 text-sm mt-2">We work with clients worldwide — fill out the form and our team will get back to you within 24 hours, wherever you are.</p>

                        <div class="mt-8 space-y-5">
                            @if($siteSettings->site_email)
                            <a href="mailto:{{ $siteSettings->site_email }}" class="flex items-center gap-4 bg-white/10 hover:bg-white/15 rounded-2xl p-4 transition">
                                <span class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <span>
                                    <span class="block text-xs text-blue-200/60 uppercase tracking-widest font-bold">Email</span>
                                    <span class="block text-sm font-semibold mt-0.5 break-all">{{ $siteSettings->site_email }}</span>
                                </span>
                            </a>
                            @endif
                            @if($siteSettings->site_phone)
                            <a href="tel:{{ $siteSettings->site_phone }}" class="flex items-center gap-4 bg-white/10 hover:bg-white/15 rounded-2xl p-4 transition">
                                <span class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </span>
                                <span>
                                    <span class="block text-xs text-blue-200/60 uppercase tracking-widest font-bold">Phone</span>
                                    <span class="block text-sm font-semibold mt-0.5">{{ $siteSettings->site_phone }}</span>
                                </span>
                            </a>
                            @endif
                            @if($siteSettings->site_address)
                            <div class="flex items-center gap-4 bg-white/10 rounded-2xl p-4">
                                <span class="w-11 h-11 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </span>
                                <span>
                                    <span class="block text-xs text-blue-200/60 uppercase tracking-widest font-bold">Address</span>
                                    <span class="block text-sm font-semibold mt-0.5">{{ $siteSettings->site_address }}</span>
                                </span>
                            </div>
                            @endif
                        </div>

                        <div class="mt-6 pt-6 border-t border-white/15 flex items-center gap-2.5 text-sm text-blue-100/70">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Available across all timezones — we reply within 24 hours.</span>
                        </div>
                    </div>

                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-3">
                    <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm">
                        @csrf
                        <h3 class="text-xl font-extrabold text-slate-900 mb-6">Send us a message</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Your name"
                                       class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition @error('name') border-red-400 @enderror">
                                @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="you@example.com"
                                       class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition @error('email') border-red-400 @enderror">
                                @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Your phone number"
                                       class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-semibold text-slate-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
                                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required placeholder="How can we help?"
                                       class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition @error('subject') border-red-400 @enderror">
                                @error('subject')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-5">
                            <label for="message" class="block text-sm font-semibold text-slate-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                            <textarea name="message" id="message" rows="5" required placeholder="Tell us about your project..."
                                      class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                            @error('message')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="mt-6">
                            <button type="submit" id="contact-submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-[#1d4ed8] to-[#2f6fed] text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-blue-600/25 transition disabled:opacity-60">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Google Map — full width below --}}
            @if($siteSettings->google_map_embed_url ?? null)
            <div class="mt-8 rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
                <iframe src="{{ $siteSettings->google_map_embed_url }}" class="w-full h-80 block" style="border:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Our Location"></iframe>
            </div>
            @endif
        </div>
    </section>
@endsection

@push('seo')
<meta property="og:title" content="Contact Us | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
<meta property="og:description" content="Get in touch with {{ $siteSettings->site_name ?? 'AK Tech SOL' }} — we work with clients worldwide and reply within 24 hours.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "ContactPage",
    "name": "Contact Us | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}",
    "url": "{{ url()->current() }}",
    "about": {
        "@@type": "Organization",
        "name": "{{ $siteSettings->site_name ?? 'AK Tech SOL' }}",
        "url": "{{ url('/') }}"
        @if($siteSettings->site_email ?? null)
        ,"email": "{{ $siteSettings->site_email }}"
        @endif
        @if($siteSettings->site_phone ?? null)
        ,"telephone": "{{ $siteSettings->site_phone }}"
        @endif
    }
}
</script>
@endpush

@push('scripts')
<script>
    (function() {
        const form = document.getElementById('contact-form');
        if (!form) return;
        const btn = document.getElementById('contact-submit');
        const successBox = document.getElementById('contact-success');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            btn.disabled = true;
            btn.textContent = 'Sending...';
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (res.ok) {
                    successBox.textContent = data.message || 'Message sent! We\'ll get back to you soon.';
                    successBox.classList.remove('hidden');
                    form.reset();
                    successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    alert(data.message || 'Something went wrong. Please try again.');
                }
            } catch (err) {
                alert('Network error. Please try again.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Send Message';
            }
        });
    })();
</script>
@endpush
