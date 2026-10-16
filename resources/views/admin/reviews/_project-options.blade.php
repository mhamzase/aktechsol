{{-- Selectable delivered-project cards for the review-request picker.
     Selected-state classes live in data-* attributes (literal strings) so
     Tailwind compiles them; admin.js toggles them via paintPick(). --}}
@forelse($projects as $project)
    <button type="button"
            data-project-pick="{{ $project->id }}"
            data-pick-on="border-blue-600 ring-2 ring-blue-600/30 bg-blue-50 dark:bg-blue-500/10 dark:border-blue-500"
            data-pick-off="border-slate-200 dark:border-white/10"
            data-circle-on="bg-blue-600 border-blue-600"
            data-circle-off="border-slate-300 dark:border-slate-600"
            aria-pressed="false"
            class="w-full text-left p-3.5 rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 hover:border-blue-400 dark:hover:border-blue-500 transition flex items-start gap-3">
        <span data-pick-circle
              class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-slate-300 dark:border-slate-600 transition">
            <svg data-pick-check class="h-3 w-3 text-white opacity-0 scale-75 transition" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        </span>
        <span class="min-w-0 flex-1">
            <span class="block truncate text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $project->title }}</span>
            <span class="mt-0.5 block truncate text-xs text-slate-500 dark:text-slate-400">
                {{ $project->client_name }} &bull; {{ $project->client_email }}
            </span>
        </span>
    </button>
@empty
    <div class="rounded-xl border border-dashed border-slate-300 dark:border-white/15 p-8 text-center">
        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">No delivered projects found.</p>
        <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">Try a different search.</p>
    </div>
@endforelse
