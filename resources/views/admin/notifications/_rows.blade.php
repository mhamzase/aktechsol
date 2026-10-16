{{-- Single notification rows — reused by index + AJAX load-more --}}
@foreach ($notifications as $note)
    <div class="notif-row flex items-start gap-3 px-5 py-4 border-b border-slate-100 dark:border-slate-700 last:border-0 transition-colors {{ $note->read_at ? 'bg-white dark:bg-slate-800' : 'bg-blue-50/70 dark:bg-blue-500/10' }}"
         data-id="{{ $note->id }}">
        <span class="notif-dot w-2.5 h-2.5 rounded-full mt-1.5 shrink-0 {{ $note->read_at ? 'bg-slate-300 dark:bg-slate-600' : 'bg-[#1d4ed8]' }}"></span>
        <div class="flex-1 min-w-0">
            @if ($note->data['url'] ?? null)
                @php
                    $nurl = $note->data['url'];
                    $ntitle = $note->data['title'] ?? 'Details';
                    $inDrawer = (bool) preg_match('#/admin/(contact-messages|chatbot|quotes|invoices|client-projects|proposals|reviews)/[^/]+$#', $nurl);
                @endphp
                <a href="{{ $nurl }}" @if($inDrawer) onclick="event.preventDefault(); window.openDrawer('{{ $nurl }}', '{{ addslashes($ntitle) }}');" @endif class="block group">
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 group-hover:text-[#1d4ed8] dark:group-hover:text-blue-400">{{ $note->data['title'] ?? 'Notification' }}</p>
                    <p class="text-sm text-slate-600 dark:text-slate-300 mt-0.5">{{ $note->data['message'] ?? '' }}</p>
                </a>
            @else
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100">{{ $note->data['title'] ?? 'Notification' }}</p>
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-0.5">{{ $note->data['message'] ?? '' }}</p>
            @endif
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ $note->created_at->diffForHumans() }} · {{ $note->created_at->format('d M Y, h:i A') }}</p>
        </div>
        <div class="flex items-center gap-1.5 shrink-0">
            @unless ($note->read_at)
                <button class="notif-read-btn w-8 h-8 rounded-lg flex items-center justify-center text-[#1d4ed8] dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-500/20 transition-colors"
                        title="Mark as read" onclick="confirmMarkRead('{{ $note->id }}')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </button>
            @endunless
            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-red-500 hover:bg-red-50 dark:hover:bg-red-500/20 transition-colors"
                    title="Delete" onclick="confirmDeleteNotification('{{ $note->id }}')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
        </div>
    </div>
@endforeach
