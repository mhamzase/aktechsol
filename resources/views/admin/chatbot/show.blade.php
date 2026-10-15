{{-- Chatbot query answer drawer --}}
<div class="drawer-content">
    <div class="p-6 pb-5 border-b border-slate-100 dark:border-slate-700">
        <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Visitor asked</p>
        <p class="text-[1.05rem] font-bold text-slate-900 dark:text-slate-100">{{ $query->query }}</p>
        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $query->created_at->format('M d, Y h:i A') }} · {{ $query->created_at->diffForHumans() }}</p>
    </div>

    <div class="p-6">
        <form data-ajax-form data-reload-table="chatbot-table" method="POST"
              action="{{ route('admin.chatbot.update', $query) }}">
            @csrf @method('PUT')
            <x-ui.textarea name="answer" label="Answer" rows="6"
                           :value="$query->answer"
                           placeholder="Write the answer the chatbot will give for this question…"
                           hint="HTML allowed — links and formatting work in the widget." />
            <x-admin.drawer-footer save-label="Save Answer" />
        </form>
    </div>
</div>
