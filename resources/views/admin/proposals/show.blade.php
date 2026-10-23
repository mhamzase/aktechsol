{{-- Proposal details drawer --}}
<div class="drawer-content">
    <div class="p-6 pb-5 border-b border-slate-100 dark:border-slate-700">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">{{ $proposal->title }}</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $proposal->client_name }} · {{ $proposal->client_email }}</p>
            </div>
            @php
                $statusStyles = [
                    'draft' => 'bg-slate-100 text-slate-600', 'sent' => 'bg-blue-100 text-blue-700',
                    'accepted' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700',
                    'changes_requested' => 'bg-amber-100 text-amber-700', 'expired' => 'bg-slate-100 text-slate-500',
                ];
            @endphp
            <span class="shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $statusStyles[$proposal->status] ?? 'bg-slate-100 text-slate-600' }}">
                {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
            </span>
        </div>
    </div>

    <div class="p-6 space-y-5">
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Amount</p>
                <p class="text-lg font-extrabold text-slate-800 dark:text-slate-100 mt-1">{{ \App\Support\Money::format($proposal->amount, $proposal->currency) }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Timeline</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $proposal->timeline ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Valid Until</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $proposal->valid_until?->format('M d, Y') ?? 'No expiry' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Sent</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $proposal->sent_at?->format('M d, Y') ?? 'Not sent' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Viewed</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $proposal->viewed_at?->format('M d, Y h:i A') ?? 'Not viewed' }}</p>
            </div>
        </div>

        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Scope</p>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-4 text-slate-700 dark:text-slate-200 text-sm leading-relaxed">{!! $proposal->scope !!}</div>
        </div>

        @if($proposal->milestones)
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Milestones</p>
            <div class="space-y-2">
                @foreach($proposal->milestones as $m)
                <div class="flex justify-between bg-slate-50 dark:bg-slate-800/60 rounded-xl px-4 py-2.5 text-sm">
                    <span class="font-medium text-slate-700 dark:text-slate-200">{{ $m['title'] }}</span>
                    <span class="font-bold text-slate-800 dark:text-slate-100">{{ \App\Support\Money::format($m['amount'], $proposal->currency) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($proposal->client_note)
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Client Note</p>
            <div class="bg-blue-50/60 dark:bg-blue-500/10 border border-blue-100 rounded-xl p-4 text-sm text-slate-700 dark:text-slate-200">{{ $proposal->client_note }}</div>
        </div>
        @endif

        @if($proposal->responses->isNotEmpty())
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Feedback History</p>
            <div class="space-y-2.5">
                @foreach($proposal->responses->sortByDesc('created_at') as $entry)
                    @php
                        if ($entry->type === 'revised') {
                            $entryLabel = 'Revised & sent';
                            $entryBox = 'bg-blue-50/60 dark:bg-blue-500/10 border-blue-100';
                            $entryBadge = 'bg-blue-100 text-blue-700';
                        } else {
                            $entryLabel = [
                                'accepted' => 'Client accepted',
                                'changes_requested' => 'Client requested changes',
                                'rejected' => 'Client declined',
                            ][$entry->action] ?? 'Client responded';
                            $entryBox = $entry->action === 'accepted'
                                ? 'bg-green-50/60 dark:bg-green-500/10 border-green-100'
                                : ($entry->action === 'rejected' ? 'bg-red-50/60 dark:bg-red-500/10 border-red-100' : 'bg-amber-50/60 dark:bg-amber-500/10 border-amber-100');
                            $entryBadge = $entry->action === 'accepted'
                                ? 'bg-green-100 text-green-700'
                                : ($entry->action === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700');
                        }
                    @endphp
                    <div class="border rounded-xl p-4 text-sm {{ $entryBox }}">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $entryBadge }}">{{ $entryLabel }}</span>
                            <span class="text-xs text-slate-400">{{ $entry->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        @if($entry->type === 'revised')
                            <p class="mt-1.5 text-slate-600 dark:text-slate-300">Updated proposal sent to the client.</p>
                        @elseif($entry->note)
                            <p class="mt-1.5 text-slate-700 dark:text-slate-200">{{ $entry->note }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex flex-wrap gap-3 pt-2">
            @if($proposal->status === 'draft')
            <form data-ajax-form data-reload-table="proposals-table" method="POST" action="{{ route('admin.proposals.send', $proposal) }}" class="w-full"
                  data-confirm-title="Mark as Sent?"
                  data-confirm-message="This will mark the proposal as sent and email it to {{ $proposal->client_email }}. Continue?"
                  data-confirm-label="Send Proposal">
                @csrf
                <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 text-base font-extrabold text-white rounded-2xl transition shadow-lg"
                        style="background: linear-gradient(135deg, #1d4ed8 0%, #7c3aed 100%); box-shadow: 0 8px 24px rgba(29,78,216,0.35);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Mark as Sent & Email Client
                </button>
            </form>
            @endif
        <div class="flex flex-wrap gap-2.5 pt-1">
            @if($proposal->status === 'changes_requested')
            <button type="button" onclick="window.openDrawer('{{ route('admin.proposals.edit', $proposal) }}?revise=1', 'Revise & Resend')"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-bold text-white bg-amber-500 rounded-xl hover:bg-amber-600 transition shadow-sm shadow-amber-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Revise & Resend
            </button>
            @endif
            @if($proposal->status !== 'accepted')
            <button type="button" data-resend-proposal data-url="{{ route('admin.proposals.resend', $proposal) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Resend Email
            </button>
            @endif
            <button type="button" data-duplicate-proposal data-url="{{ route('admin.proposals.duplicate', $proposal) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-bold text-white bg-violet-600 rounded-xl hover:bg-violet-700 transition shadow-sm shadow-violet-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Duplicate
            </button>
            {{-- Preview + Copy as one split button --}}
            <div class="inline-flex items-stretch rounded-xl overflow-hidden shadow-sm">
                <a href="{{ $proposal->publicUrl() }}" target="_blank"
                   class="inline-flex items-center gap-1.5 pl-4 pr-3 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview
                </a>
                <button type="button" title="Copy Client Link" onclick="navigator.clipboard.writeText('{{ $proposal->publicUrl() }}'); window.notify.success('Client link copied!')"
                        class="inline-flex items-center px-3 text-white bg-blue-600 hover:bg-blue-700 border-l border-white/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
            </div>
            <a href="{{ route('admin.proposals.pdf', $proposal) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition shadow-sm shadow-emerald-600/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Download PDF
            </a>
        </div>
        </div>
    </div>
</div>
