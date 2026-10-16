@extends('layouts.app')

@section('title', 'Leave a Review')

@section('content')
<div class="min-h-screen bg-slate-50 py-6 md:py-10 px-4">
    <div class="max-w-xl mx-auto space-y-5 md:space-y-6">

        {{-- Hero --}}
        <div class="rounded-3xl p-6 md:p-8 text-white text-center" style="background: linear-gradient(135deg, #1d4ed8 0%, #12307e 100%);">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-200/70">Your Feedback</p>
            <h1 class="text-2xl md:text-3xl font-extrabold mt-2">How was your experience?</h1>
            @if($project)
            <p class="text-blue-200/80 text-sm mt-1.5">Project: <strong class="text-white">{{ $project->title }}</strong></p>
            @endif
        </div>

        @if($alreadySubmitted)
        {{-- Already submitted state --}}
        <div class="bg-white rounded-3xl p-8 md:p-10 border border-slate-100 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h2 class="text-xl font-extrabold text-slate-900">Thank you!</h2>
            <p class="text-sm text-slate-500 mt-2">Your review has been received and is awaiting moderation.</p>
        </div>
        @elseif(!$projectDelivered)
        {{-- Not active --}}
        <div class="bg-white rounded-3xl p-8 md:p-10 border border-slate-100 text-center">
            <h2 class="text-xl font-extrabold text-slate-900">Link not active</h2>
            <p class="text-sm text-slate-500 mt-2">This review link becomes active once your project is delivered.</p>
        </div>
        @else
        {{-- Review form --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100">
            <form id="review-form" action="{{ route('reviews.submit', $review->token) }}" method="POST" class="space-y-5">
                @csrf

                {{-- Star rating --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Your Rating <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-1" id="star-picker">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" data-star="{{ $i }}" class="p-1 transition-transform hover:scale-110" aria-label="{{ $i }} star">
                            <svg class="w-9 h-9 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.29 3.96a1 1 0 00.95.69h4.16c.97 0 1.37 1.24.59 1.81l-3.37 2.45a1 1 0 00-.36 1.12l1.28 3.95c.3.93-.75 1.7-1.54 1.13l-3.36-2.44a1 1 0 00-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.53-1.13l1.28-3.95a1 1 0 00-.37-1.12L2.07 9.39c-.78-.57-.38-1.81.6-1.81h4.15a1 1 0 00.95-.69l1.28-3.96z"/></svg>
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="5">
                    <p class="text-xs text-slate-400 mt-1" id="rating-label">Excellent — 5 stars</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Your Name <span class="text-red-500">*</span></label>
                    <input type="text" name="reviewer_name" required maxlength="255" value="{{ $review->reviewer_name }}"
                           placeholder="Your full name"
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Your Review <span class="text-red-500">*</span></label>
                    <textarea name="review" required minlength="20" maxlength="2000" rows="4"
                              placeholder="Tell us about your experience working with us… (minimum 20 characters)"
                              class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>
                    <p class="text-xs text-slate-400 mt-1"><span id="review-count">0</span>/2000 · minimum 20 characters</p>
                </div>

                <button type="submit" id="review-submit-btn"
                        class="w-full px-8 py-3.5 text-white font-bold rounded-2xl transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #1d4ed8, #2f6fed);">
                    Submit Review
                </button>
            </form>
        </div>
        @endif

        <p class="text-center text-xs text-slate-400">
            Thank you for choosing AK Tech SOL.
        </p>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const form = document.getElementById('review-form');
    if (!form) return;

    // Star picker.
    const stars = document.querySelectorAll('#star-picker [data-star]');
    const ratingInput = document.getElementById('rating-input');
    const ratingLabel = document.getElementById('rating-label');
    const labels = { 1: 'Poor', 2: 'Fair', 3: 'Good', 4: 'Very Good', 5: 'Excellent' };
    function paintStars(n) {
        stars.forEach(s => {
            const svg = s.querySelector('svg');
            const active = parseInt(s.dataset.star, 10) <= n;
            svg.classList.toggle('text-amber-400', active);
            svg.classList.toggle('text-slate-300', !active);
        });
        ratingLabel.textContent = `${labels[n]} — ${n} star${n > 1 ? 's' : ''}`;
    }
    stars.forEach(s => s.addEventListener('click', () => {
        ratingInput.value = s.dataset.star;
        paintStars(parseInt(s.dataset.star, 10));
    }));
    paintStars(5);

    // Character counter.
    const textarea = form.querySelector('[name="review"]');
    const counter = document.getElementById('review-count');
    textarea.addEventListener('input', () => { counter.textContent = textarea.value.length; });

    // AJAX submit.
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const review = textarea.value.trim();
        if (review.length < 20) {
            window.notify.error('Please write at least 20 characters.');
            textarea.focus();
            return;
        }
        const btn = document.getElementById('review-submit-btn');
        btn.disabled = true;
        btn.textContent = 'Submitting…';
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json().catch(() => ({}));
            if (res.ok) {
                location.reload();
            } else if (res.status === 422 && data.errors) {
                window.notify.error(Object.values(data.errors)[0]?.[0] || 'Please check your input.');
            } else {
                window.notify.error(data.message || 'Something went wrong.');
            }
        } catch (err) {
            window.notify.error('Network error. Please try again.');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Submit Review';
        }
    });
})();
</script>
@endpush
@endsection
