{{-- Drawer form: edit review. AJAX submit (data-ajax-form), no page refresh. --}}
<div class="drawer-content">
    <form data-ajax-form data-reload-table="reviews-table" method="POST"
          action="{{ route('admin.reviews.update', $review) }}" novalidate
          class="px-6 pb-6 pt-2">
        @csrf
        @method('PUT')

        @if($review->project)
            <p class="mb-4 text-sm text-slate-500 dark:text-slate-400">
                Project: <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $review->project->title }}</span>
            </p>
        @endif

        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 md:col-span-6">
                <x-ui.input name="reviewer_name" label="Reviewer Name" :value="old('reviewer_name', $review->reviewer_name)" required placeholder="Client name" />
            </div>
            <div class="col-span-6 md:col-span-3">
                <x-ui.select name="rating" label="Rating" required
                             :options="['5' => '5 ★', '4' => '4 ★', '3' => '3 ★', '2' => '2 ★', '1' => '1 ★']"
                             :value="old('rating', $review->rating)" />
            </div>
            <div class="col-span-6 md:col-span-3">
                <x-ui.select name="status" label="Status" required
                             :options="['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected']"
                             :value="old('status', $review->status)" />
            </div>
            <div class="col-span-12">
                <x-ui.textarea name="review" label="Review" :value="old('review', $review->review)" rows="4"
                               placeholder="Client's review text (min 20 characters)…" />
            </div>
        </div>

        <x-admin.drawer-footer save-label="Save Changes" />
    </form>
</div>
