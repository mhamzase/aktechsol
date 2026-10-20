@extends('layouts.app')

@section('title', 'Get a Quote | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Request a free quote for your project from ' . ($siteSettings->site_name ?? 'AK Tech SOL') . '. Tell us about your requirements and get a response within 24 hours.')

@push('seo')
<meta property="og:title" content="Get a Free Quote | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
<meta property="og:description" content="Tell us about your project and get a free, no-obligation quote within 24 hours.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
@endpush

@section('content')
    <x-frontend.page-banner
        title="Get a Free Quote"
        subtitle="Tell us about your project. We'll respond within 24 hours."
    />

    <section class="py-12 sm:py-16 bg-slate-50/60">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div id="quote-success" class="hidden mb-6 p-5 bg-green-50 border border-green-200 text-green-700 rounded-2xl">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold">Request received!</p>
                        <p class="text-sm mt-1">Thank you for reaching out. Our team will review your requirements and get back to you within 24 hours.</p>
                    </div>
                </div>
            </div>

            <form id="quote-form" action="{{ route('quote.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-sm">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required placeholder="Your name"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                        <p class="err text-xs text-red-600 mt-1.5 hidden" data-err="name"></p>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" required placeholder="you@example.com"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                        <p class="err text-xs text-red-600 mt-1.5 hidden" data-err="email"></p>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1.5">Phone</label>
                        <input type="text" name="phone" id="phone" placeholder="Your phone number"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                    </div>
                    <div>
                        <label for="company" class="block text-sm font-semibold text-slate-700 mb-1.5">Company</label>
                        <input type="text" name="company" id="company" placeholder="Company name (optional)"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                    </div>
                    <div>
                        <label for="service_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Service Needed</label>
                        <select name="service_id" id="service_id"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition bg-white">
                            <option value="">Select a service...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ (string)$selectedService === (string)$service->id ? 'selected' : '' }}>{{ $service->title }}</option>
                            @endforeach
                            <option value="">Other / Not sure yet</option>
                        </select>
                    </div>
                    <div>
                        <label for="budget_range" class="block text-sm font-semibold text-slate-700 mb-1.5">Budget Range</label>
                        <select name="budget_range" id="budget_range"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition bg-white">
                            <option value="">Select budget...</option>
                            <option value="Under $500">Under $500</option>
                            <option value="$500 - $1,000">$500 – $1,000</option>
                            <option value="$1,000 - $5,000">$1,000 – $5,000</option>
                            <option value="$5,000 - $10,000">$5,000 – $10,000</option>
                            <option value="$10,000+">$10,000+</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="subject" class="block text-sm font-semibold text-slate-700 mb-1.5">Subject</label>
                        <input type="text" name="subject" id="subject" placeholder="Brief subject of your project"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                    </div>
                    <div class="sm:col-span-2">
                        <label for="requirements" class="block text-sm font-semibold text-slate-700 mb-1.5">Project Requirements <span class="text-red-500">*</span></label>
                        <textarea name="requirements" id="requirements" rows="6" required placeholder="Describe your project in detail: goals, features, timeline, anything that helps us understand..."
                                  class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition"></textarea>
                        <p class="err text-xs text-red-600 mt-1.5 hidden" data-err="requirements"></p>
                        <p class="text-xs text-slate-400 mt-1.5">Minimum 20 characters. The more detail, the better the quote.</p>
                    </div>
                </div>
                <div class="mt-7">
                    <button type="submit" id="quote-submit" class="w-full px-8 py-4 bg-gradient-to-r from-[#1d4ed8] to-[#2f6fed] text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-blue-600/25 transition disabled:opacity-60 text-base">
                        Request Free Quote
                    </button>
                    <p class="text-xs text-slate-400 text-center mt-3">No spam, no obligation. We reply within 24 hours.</p>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function() {
        const form = document.getElementById('quote-form');
        if (!form) return;
        const btn = document.getElementById('quote-submit');
        const successBox = document.getElementById('quote-success');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            // Clear old errors
            form.querySelectorAll('.err').forEach(el => { el.classList.add('hidden'); el.textContent = ''; });
            btn.disabled = true;
            btn.textContent = 'Sending...';
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                let data = {};
                try { data = await res.json(); } catch (e) { /* non-JSON response (e.g. 500 page) */ }
                if (res.ok) {
                    window.notify.success(data.message || 'Quote request sent successfully!');
                    successBox.classList.remove('hidden');
                    form.reset();
                    successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else if (res.status === 422 && data.errors) {
                    Object.entries(data.errors).forEach(([field, msgs]) => {
                        const el = form.querySelector(`[data-err="${field}"]`);
                        if (el) { el.textContent = msgs[0]; el.classList.remove('hidden'); }
                    });
                    window.notify.error('Please fix the highlighted fields.');
                    form.querySelector('.err:not(.hidden)')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else if (res.status === 419) {
                    window.notify.error('Session expired. Please refresh the page and try again.');
                } else {
                    window.notify.error(data.message || `Server error (${res.status}). Please try again.`);
                }
            } catch (err) {
                window.notify.error('Network error. Please check your connection and try again.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Request Free Quote';
            }
        });
    })();
</script>
@endpush
