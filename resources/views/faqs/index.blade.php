@extends('layouts.app')

@section('title', 'FAQs — Frequently Asked Questions | ' . ($siteSettings->site_name ?? 'AK Tech SOL'))
@section('meta_description', 'Find answers to frequently asked questions about ' . ($siteSettings->site_name ?? 'AK Tech SOL') . ' services, process, pricing and support.')

@push('seo')
<meta property="og:title" content="FAQs | {{ $siteSettings->site_name ?? 'AK Tech SOL' }}">
<meta property="og:description" content="Answers to common questions about our services, process, pricing and support.">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FAQPage",
    "mainEntity": [
        @foreach($faqs as $faq)
        {
            "@@type": "Question",
            "name": {!! json_encode($faq->question) !!},
            "acceptedAnswer": {
                "@@type": "Answer",
                "text": {!! json_encode(strip_tags($faq->answer)) !!}
            }
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
@endpush

@section('content')
    <x-frontend.page-banner
        title="Frequently Asked Questions"
        subtitle="Everything you need to know about working with us."
    />

    <section class="py-12 sm:py-16 bg-slate-50/60">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            {{-- Search --}}
            <div class="relative mb-8">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" id="faq-search" placeholder="Search questions..."
                       class="w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 py-3.5 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none transition shadow-sm">
            </div>

            <div id="faq-list" class="space-y-3">
                @forelse($faqs as $index => $faq)
                <div class="faq-item bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-md transition-shadow" data-question="{{ strtolower($faq->question . ' ' . strip_tags($faq->answer)) }}">
                    <button type="button" onclick="toggleFaq(this)"
                            class="w-full flex items-center justify-between gap-4 px-5 sm:px-6 py-4 sm:py-5 text-left">
                        <span class="font-bold text-slate-900 text-[15px] sm:text-base">{{ $faq->question }}</span>
                        <span class="faq-icon shrink-0 w-9 h-9 rounded-xl bg-[#1d4ed8]/10 text-[#1d4ed8] flex items-center justify-center transition-transform duration-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        </span>
                    </button>
                    <div class="faq-answer hidden px-5 sm:px-6 pb-5 sm:pb-6 text-slate-600 text-sm sm:text-[15px] leading-relaxed">
                        {!! $faq->answer !!}
                    </div>
                </div>
                @empty
                <div class="text-center py-16">
                    <div class="w-16 h-16 mx-auto bg-white rounded-2xl flex items-center justify-center mb-4 shadow-sm">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.464-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">No FAQs yet</h3>
                    <p class="text-sm text-slate-500 mt-1">Check back soon for answers to common questions.</p>
                </div>
                @endforelse
            </div>

            <div id="faq-empty" class="hidden text-center py-12">
                <p class="text-slate-500 font-medium">No questions match your search.</p>
            </div>

            {{-- CTA --}}
            <div class="mt-10 bg-gradient-to-r from-[#1d4ed8] to-[#12307e] rounded-3xl p-8 text-center text-white">
                <h3 class="text-xl font-extrabold">Still have questions?</h3>
                <p class="text-blue-100/80 text-sm mt-2">We'd love to hear from you — we reply within 24 hours.</p>
                <a href="{{ url('/contact') }}" class="mt-5 inline-block px-7 py-3 bg-white text-[#12307e] font-bold rounded-2xl hover:bg-blue-50 transition text-sm">Contact Us</a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function toggleFaq(button) {
        const item = button.closest('.faq-item');
        const answer = item.querySelector('.faq-answer');
        const icon = item.querySelector('.faq-icon');
        const isOpen = !answer.classList.contains('hidden');

        // Close all
        document.querySelectorAll('.faq-item').forEach(el => {
            el.querySelector('.faq-answer').classList.add('hidden');
            el.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
        });

        // Open clicked (if it was closed)
        if (!isOpen) {
            answer.classList.remove('hidden');
            icon.style.transform = 'rotate(45deg)';
        }
    }

    (function() {
        const search = document.getElementById('faq-search');
        const emptyMsg = document.getElementById('faq-empty');
        if (!search) return;
        search.addEventListener('input', () => {
            const q = search.value.toLowerCase().trim();
            let visible = 0;
            document.querySelectorAll('.faq-item').forEach(item => {
                const match = !q || item.dataset.question.includes(q);
                item.classList.toggle('hidden', !match);
                if (match) visible++;
            });
            emptyMsg.classList.toggle('hidden', visible > 0);
        });
    })();
</script>
@endpush
