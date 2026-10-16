{{-- Send review request drawer form --}}
<div class="drawer-content">
    <form data-ajax-form method="POST" action="{{ route('admin.reviews.send-request') }}"
          class="px-6 pb-6 pt-2 space-y-4">
        @csrf
        <p class="text-sm text-slate-500 dark:text-slate-400">
            Emails a review request to the client. Only delivered projects are listed.
        </p>
        <x-ui.search-select name="client_project_id" label="Delivered Project" required
                            placeholder="Select a project…"
                            :options="$projects->mapWithKeys(fn($p) => [$p->id => $p->title . ' (' . $p->client_name . ')'])->toArray()" />
        <x-admin.drawer-footer save-label="Send Request" />
    </form>
</div>
