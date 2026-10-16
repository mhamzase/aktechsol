{{-- Drawer form: edit review. AJAX submit (data-ajax-form), no page refresh. --}}
@php
    $ratingLabels = [1 => 'Poor', 2 => 'Fair', 3 => 'Good', 4 => 'Very Good', 5 => 'Excellent'];
    $currentRating = (int) old('rating', $review->rating ?? 5);
    $currentStatus = old('status', $review->status ?? 'pending');
    $statusPills = [
        'pending'  => ['label' => 'Pending',  'on' => 'bg-amber-500 text-white shadow'],
        'approved' => ['label' => 'Approved', 'on' => 'bg-green-600 text-white shadow'],
        'rejected' => ['label' => 'Rejected', 'on' => 'bg-red-500 text-white shadow'],
    ];
@endphp
<div class="drawer-content">
    <form data-ajax-form data-reload-table="reviews-table" method="POST"
          action="{{ route('admin.reviews.update', $review) }}" novalidate
          class="px-6 pb-6 pt-2 space-y-5">
        @csrf
        @method('PUT')

        {{-- Project card --}}
        @if($review->project)
            <div class="flex items-center gap-3 rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 p-3.5">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-600/10 text-blue-600 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.9 23.9 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                <span class="min-w-0">
                    <span class="block text-[11px] font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Project</span>
                    <span class="block truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $review->project->title }}</span>
                    <span class="block truncate text-xs text-slate-500 dark:text-slate-400">{{ $review->project->client_name }}</span>
                </span>
            </div>
        @endif

        <x-ui.input name="reviewer_name" label="Reviewer Name" :value="old('reviewer_name', $review->reviewer_name)" required placeholder="Client name" />

        {{-- Rating: interactive star picker --}}
        <div>
            <span class="ui-label">Rating <span class="ui-required">*</span></span>
            <div class="flex items-center gap-1" data-star-picker>
                @for($i = 1; $i <= 5; $i++)
                    <button type="button" data-rate-star="{{ $i }}" class="p-1 transition-transform hover:scale-110" aria-label="{{ $i }} star">
                        <svg class="w-8 h-8 {{ $i <= $currentRating ? 'text-amber-400' : 'text-slate-300 dark:text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.29 3.96a1 1 0 00.95.69h4.16c.97 0 1.37 1.24.59 1.81l-3.37 2.45a1 1 0 00-.36 1.12l1.28 3.95c.3.93-.75 1.7-1.54 1.13l-3.36-2.44a1 1 0 00-1.18 0l-3.36 2.44c-.78.57-1.84-.2-1.53-1.13l1.28-3.95a1 1 0 00-.37-1.12L2.07 9.39c-.78-.57-.38-1.81.6-1.81h4.15a1 1 0 00.95-.69l1.28-3.96z" />
                        </svg>
                    </button>
                @endfor
            </div>
            <input type="hidden" name="rating" value="{{ $currentRating }}" data-rating-value>
            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500" data-rating-label>{{ $ratingLabels[$currentRating] }} &mdash; {{ $currentRating }} star{{ $currentRating > 1 ? 's' : '' }}</p>
        </div>

        {{-- Status: segmented pills --}}
        <div>
            <span class="ui-label">Status <span class="ui-required">*</span></span>
            <input type="hidden" name="status" value="{{ $currentStatus }}" data-status-value>
            <div class="flex gap-1 rounded-full bg-slate-100 dark:bg-white/5 p-1" data-status-pills>
                @foreach($statusPills as $val => $meta)
                    <button type="button" data-status-pill="{{ $val }}"
                            data-pill-on="{{ $meta['on'] }}"
                            data-pill-off="text-slate-500 dark:text-slate-400"
                            class="flex-1 rounded-full px-3 py-1.5 text-xs font-semibold transition {{ $currentStatus === $val ? $meta['on'] : 'text-slate-500 dark:text-slate-400' }}">{{ $meta['label'] }}</button>
                @endforeach
            </div>
        </div>

        {{-- Review text with character counter --}}
        <div>
            <x-ui.textarea name="review" label="Review" :value="old('review', $review->review)" rows="4"
                           data-review-text placeholder="Client's review text…" />
            <p class="mt-1 text-right text-xs text-slate-400 dark:text-slate-500">
                <span data-review-count>{{ mb_strlen(old('review', $review->review ?? '')) }}</span> characters
                <span class="opacity-70">(min 20)</span>
            </p>
        </div>

        <x-admin.drawer-footer save-label="Save Changes" />
    </form>
</div>
