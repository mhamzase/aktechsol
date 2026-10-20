@extends('layouts.app')

@section('title', $job->title . ' | Careers at ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($job->description), 155))

@push('seo')
<meta property="og:title" content="{{ $job->title }} | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
<meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($job->description), 155) }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
@endpush

@section('content')
    <x-frontend.page-banner
        :title="$job->title"
        :subtitle="($job->type === 'internship' ? 'Internship' : 'Full-time Job') . ' · ' . ucfirst($job->location_type) . ' · Posted ' . $job->created_at->format('M d, Y')"
    />

    <section class="py-12 sm:py-16 bg-slate-50/60">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            <a href="{{ route('careers.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-[#1d4ed8] hover:underline mb-6">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                All open positions
            </a>

            {{-- Description --}}
            <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-sm">
                <h2 class="text-lg font-extrabold text-slate-900 mb-4">About this role</h2>
                <div class="prose prose-slate max-w-none leading-relaxed">{!! $job->description !!}</div>

                @if($job->requirements)
                    <h2 class="text-lg font-extrabold text-slate-900 mt-8 mb-4">Requirements</h2>
                    <div class="prose prose-slate max-w-none leading-relaxed">{!! $job->requirements !!}</div>
                @endif
            </div>

            {{-- Apply --}}
            <div id="apply-success" class="hidden mt-6 p-5 bg-green-50 border border-green-200 text-green-700 rounded-2xl">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold">Application submitted!</p>
                        <p class="text-sm mt-1">Thank you for applying. Our team will review your portfolio and get back to you if you're shortlisted.</p>
                    </div>
                </div>
            </div>

            <form id="apply-form" action="{{ route('careers.apply', $job->slug) }}" method="POST" enctype="multipart/form-data" class="mt-6 bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-sm">
                @csrf
                <h2 class="text-lg font-extrabold text-slate-900 mb-1.5">Apply for this position</h2>
                <p class="text-sm text-slate-500 mb-6">Your portfolio matters more than your resume — applications without work samples are not reviewed.</p>
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
                        <label for="portfolio_url" class="block text-sm font-semibold text-slate-700 mb-1.5">Portfolio URL <span class="text-red-500">*</span></label>
                        <input type="url" name="portfolio_url" id="portfolio_url" required placeholder="https://behance.net/you or your best work"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition">
                        <p class="err text-xs text-red-600 mt-1.5 hidden" data-err="portfolio_url"></p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="resume" class="block text-sm font-semibold text-slate-700 mb-1.5">Resume / CV <span class="text-slate-400 font-normal">(PDF, optional, max 5MB)</span></label>
                        <input type="file" name="resume" id="resume" accept="application/pdf"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition bg-white">
                        <p class="err text-xs text-red-600 mt-1.5 hidden" data-err="resume"></p>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="cover_note" class="block text-sm font-semibold text-slate-700 mb-1.5">Cover Note</label>
                        <textarea name="cover_note" id="cover_note" rows="5" placeholder="A few lines about yourself and why you're a good fit…"
                                  class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition"></textarea>
                        <p class="err text-xs text-red-600 mt-1.5 hidden" data-err="cover_note"></p>
                    </div>
                </div>
                <div class="mt-7">
                    <button type="submit" id="apply-submit" class="w-full px-8 py-4 bg-gradient-to-r from-[#1d4ed8] to-[#2f6fed] text-white font-bold rounded-2xl hover:shadow-xl hover:shadow-blue-600/25 transition disabled:opacity-60 text-base">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    (function() {
        const form = document.getElementById('apply-form');
        if (!form) return;
        const btn = document.getElementById('apply-submit');
        const successBox = document.getElementById('apply-success');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            form.querySelectorAll('.err').forEach(el => { el.classList.add('hidden'); el.textContent = ''; });
            btn.disabled = true;
            btn.textContent = 'Submitting...';
            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                let data = {};
                let isJson = false;
                try { data = await res.json(); isJson = true; } catch (e) { /* non-JSON response (e.g. a redirect to login) */ }
                if (res.ok && isJson) {
                    window.notify.success(data.message || 'Application submitted successfully!');
                    successBox.classList.remove('hidden');
                    form.reset();
                    successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else if (res.ok) {
                    // 2xx but not JSON (e.g. redirected to login) — never fake a success.
                    window.notify.error('Unexpected response — your session may have expired. Please refresh the page and try again.');
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
                btn.textContent = 'Submit Application';
            }
        });
    })();
</script>
@endpush
