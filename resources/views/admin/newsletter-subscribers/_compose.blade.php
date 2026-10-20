{{-- Drawer form: compose + queue a bulk email to all subscribed users. --}}
<form data-ajax-form method="POST" action="{{ route('admin.newsletter-subscribers.send') }}" novalidate>
    @csrf

    <div class="mb-5">
        <h3 class="section-heading">
            <span class="sec-icon sec-blue">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </span>
            Write Email
        </h3>
        <div class="space-y-4">
            <x-ui.input name="subject" label="Subject" required placeholder="e.g. Our latest work and a special offer" />
            <x-ui.rich-text name="body" label="Email Body" required placeholder="Write your email here. Use headings, lists and links to make it look great…" />
        </div>
    </div>

    <div class="flex items-start gap-2.5 bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl p-3.5 mb-5">
        <svg class="w-5 h-5 text-[#1d4ed8] dark:text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
            This email will be <strong>queued</strong> and sent in the background to
            <strong>{{ $count }} subscribed</strong> user(s). Unsubscribed users will not receive it.
            Make sure the queue worker is running for delivery.
        </p>
    </div>

    <x-admin.drawer-footer save-label="Queue Email for Sending" />
</form>
