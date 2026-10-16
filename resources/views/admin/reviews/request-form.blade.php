{{-- Send review request drawer: searchable delivered-project picker --}}
<div class="drawer-content">
    <form data-ajax-form data-project-picker method="POST" action="{{ route('admin.reviews.send-request') }}"
          class="px-6 pb-6 pt-2 space-y-4">
        @csrf
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Pick a delivered project to email its client a review request.
        </p>

        <input type="hidden" name="client_project_id" value="" data-project-value>

        {{-- Search (AJAX) --}}
        <div class="relative">
            <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
            </svg>
            <input type="text" autocomplete="off"
                   data-project-search
                   data-search-url="{{ route('admin.reviews.request-form') }}"
                   placeholder="Search by project, client or email…"
                   class="w-full rounded-full border border-slate-200 dark:border-white/10 bg-white dark:bg-white/5 py-2.5 pl-10 pr-10 text-sm text-slate-800 dark:text-slate-100 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/30">
            <button type="button" data-project-search-clear
                    class="hidden absolute right-3 top-1/2 -translate-y-1/2 flex h-5 w-5 items-center justify-center rounded-full bg-slate-200 dark:bg-white/10 text-slate-500 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-white/20 text-xs leading-none"
                    aria-label="Clear search">&times;</button>
        </div>

        <p class="text-xs text-slate-400 dark:text-slate-500">
            <span data-project-count>{{ $projects->count() }}</span> delivered project(s)
        </p>

        {{-- Scrollable project list --}}
        <div data-project-list data-error-ring="ring-2 ring-red-500/50"
             class="space-y-2.5 max-h-[46vh] overflow-y-auto pr-1 rounded-xl">
            @include('admin.reviews._project-options', ['projects' => $projects])
        </div>
        <p data-project-error class="hidden text-sm text-red-600 dark:text-red-400">Please select a project.</p>

        <x-admin.drawer-footer save-label="Send Request" />
    </form>
</div>
