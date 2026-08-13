@extends('layouts.app')

@section('title', 'FAQs')
@section('meta_description', 'Frequently asked questions about AK Tech SOL.')

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-4xl font-extrabold text-center text-gray-900">Frequently Asked Questions</h1>
        <p class="text-center text-gray-500 mt-2">Find answers to common questions.</p>

        <div class="mt-12 space-y-4">
            @forelse($faqs as $index => $faq)
            <div class="bg-gray-50 rounded-xl border border-gray-100 overflow-hidden">
                <button type="button" onclick="toggleFaq(this)"
                        class="w-full flex items-center justify-between px-5 py-4 text-left text-lg font-semibold text-gray-900 hover:bg-gray-100 transition">
                    <span>{{ $faq->question }}</span>
                    <svg class="w-5 h-5 text-blue-600 transition-transform duration-200 {{ $index === 0 ? 'rotate-180' : '' }}"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="faq-answer px-5 pb-5 text-gray-700 leading-relaxed {{ $index === 0 ? '' : 'hidden' }}">
                    {{ $faq->answer }}
                </div>
            </div>
            @empty
            <p class="text-center text-gray-500">No FAQs available.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function toggleFaq(button) {
        const answer = button.nextElementSibling;
        const chevron = button.querySelector('svg');
        if (answer) {
            answer.classList.toggle('hidden');
        }
        if (chevron) {
            chevron.classList.toggle('rotate-180');
        }
    }
</script>
@endpush
