@extends('layouts.app')

@section('title', 'Book a Free Demo | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Book a free live demo with ' . ($siteSettings->site_name ?? 'AK Tech SOL') . '. Pick a date and time that suits you and our team will walk you through everything.')

@push('seo')
<meta property="og:title" content="Book a Free Demo | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
<meta property="og:description" content="See our work in action — book a free, no-obligation live demo at a time that suits you.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
@endpush

@section('content')
    <x-frontend.page-banner
        title="Book a Free Demo"
        subtitle="Pick a date and time — our team will walk you through everything, live."
    />

    <section class="py-12 sm:py-16 bg-slate-50/60">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <div id="demo-success" class="hidden mb-6 p-5 bg-green-50 border border-green-200 text-green-700 rounded-2xl">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold">Demo booked!</p>
                        <p class="text-sm mt-1">Thank you — your demo request is in. Our team will confirm your slot by email shortly.</p>
                    </div>
                </div>
            </div>

            <form id="demo-form" action="{{ route('demo.store') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-sm">
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
                        <label for="service" class="block text-sm font-semibold text-slate-700 mb-1.5">Service You're Interested In</label>
                        <select name="service" id="service"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition bg-white">
                            <option value="">Select a service...</option>
                            @foreach($services as $service)
                                <option value="{{ $service->title }}" {{ $selectedService === $service->title ? 'selected' : '' }}>{{ $service->title }}</option>
                            @endforeach
                            <option value="Other / Not sure yet">Other / Not sure yet</option>
                        </select>
                    </div>
                    <div>
                        <label for="preferred_date" class="block text-sm font-semibold text-slate-700 mb-1.5">Preferred Date</label>
                        <input type="date" name="preferred_date" id="preferred_date" min="{{ now()->toDateString() }}"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                        <p class="err text-xs text-red-600 mt-1.5 hidden" data-err="preferred_date"></p>
                    </div>
                    <div>
                        <label for="preferred_time" class="block text-sm font-semibold text-slate-700 mb-1.5">Preferred Time <span class="text-slate-400 font-normal">(PKT)</span></label>
                        <select name="preferred_time" id="preferred_time"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition bg-white">
                            <option value="">Select a time...</option>
                            @foreach($timeSlots as $slot)
                                <option value="{{ $slot }}">{{ $slot }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="message" class="block text-sm font-semibold text-slate-700 mb-1.5">Message</label>
                        <textarea name="message" id="message" rows="5" placeholder="Anything you'd like us to cover in the demo? (optional)"
                                  class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition"></textarea>
                        <p class="err text-xs text-red-600 mt-1.5 hidden" data-err="message"></p>
                    </div>
                </div>
                <div class="mt-7">
                    <button type="submit" id="demo-submit" class="w-full px-8 py-4 bg-gradient-to-r from-[#1d4ed8] to-[#2f6fed] text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-blue-600/25 transition disabled:opacity-60 text-base">
                        Book My Free Demo
                    </button>
                    <p class="text-xs text-slate-400 text-center mt-3">No spam, no obligation. We confirm every demo personally.</p>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function() {
        const form = document.getElementById('demo-form');
        if (!form) return;
        const btn = document.getElementById('demo-submit');
        const successBox = document.getElementById('demo-success');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            // Clear old errors
            form.querySelectorAll('.err').forEach(el => { el.classList.add('hidden'); el.textContent = ''; });
            btn.disabled = true;
            btn.textContent = 'Booking...';
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                let data = {};
                try { data = await res.json(); } catch (e) { /* non-JSON response (e.g. 500 page) */ }
                if (res.ok) {
                    window.notify.success(data.message || 'Demo booked successfully!');
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
                btn.textContent = 'Book My Free Demo';
            }
        });
    })();
</script>
@endpush
