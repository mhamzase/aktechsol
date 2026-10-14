{{-- Message details drawer content --}}
<div class="drawer-content p-6">
    <div class="mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Name</p>
                <p class="text-slate-900 dark:text-slate-100 font-medium mt-1">{{ $contactMessage->name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Email</p>
                <p class="text-slate-900 dark:text-slate-100 font-medium mt-1 break-all">{{ $contactMessage->email }}</p>
            </div>
            @if($contactMessage->phone)
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Phone</p>
                <p class="text-slate-900 dark:text-slate-100 font-medium mt-1">{{ $contactMessage->phone }}</p>
            </div>
            @endif
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Received</p>
                <p class="text-slate-900 dark:text-slate-100 font-medium mt-1">{{ $contactMessage->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>
        <div class="mt-5">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Subject</p>
            <p class="text-slate-900 dark:text-slate-100 font-semibold mt-1">{{ $contactMessage->subject }}</p>
        </div>
        <div class="mt-5">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Message</p>
            <div class="text-slate-700 dark:text-slate-300 leading-relaxed mt-1 bg-slate-50 dark:bg-slate-800/60 rounded-xl p-4">{{ $contactMessage->message }}</div>
        </div>
    </div>

    <div class="border-t border-slate-200 dark:border-slate-700 pt-5">
        <h3 class="text-sm font-bold text-slate-800 dark:text-slate-100 mb-3">Update Status</h3>
        <form data-ajax-form data-reload-table="contact-messages-table" method="POST" action="{{ route('admin.contact-messages.update-status', $contactMessage) }}" class="flex items-center gap-3">
            @csrf @method('PUT')
            <x-ui.select name="status" :options="['new' => 'New', 'read' => 'Read', 'replied' => 'Replied']" :value="$contactMessage->status" />
            <button type="submit" class="btn btn-primary shrink-0">Update</button>
        </form>
    </div>
</div>
