{{-- Quote details drawer --}}
<div class="drawer-content">
    {{-- Sender header --}}
    <div class="flex items-center gap-4 p-6 pb-5 border-b border-slate-100 dark:border-slate-700">
        <span class="w-12 h-12 rounded-full bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white flex items-center justify-center font-bold text-lg shrink-0">
            {{ strtoupper(substr($quote->name, 0, 1)) }}
        </span>
        <div class="min-w-0">
            <p class="font-bold text-slate-900 dark:text-slate-100 truncate">{{ $quote->name }}</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ $quote->email }}</p>
        </div>
        @php
            $statusStyles = [
                'new' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                'reviewed' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                'quoted' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
            ];
        @endphp
        <span class="ml-auto shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $statusStyles[$quote->status] ?? 'bg-slate-100 text-slate-600' }}">
            {{ ucfirst($quote->status) }}
        </span>
    </div>

    <div class="p-6 space-y-5">
        {{-- Meta grid --}}
        <div class="grid grid-cols-2 gap-4">
            @if($quote->phone)
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Phone</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $quote->phone }}</p>
            </div>
            @endif
            @if($quote->company)
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Company</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $quote->company }}</p>
            </div>
            @endif
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Service</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $quote->service?->title ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Budget</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $quote->budget_range ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Received</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $quote->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500">{{ $quote->created_at->format('h:i A') }} · {{ $quote->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Subject --}}
        @if($quote->subject)
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Subject</p>
            <p class="text-[1.05rem] font-bold text-slate-900 dark:text-slate-100">{{ $quote->subject }}</p>
        </div>
        @endif

        {{-- Requirements --}}
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Requirements</p>
            <div class="bg-blue-50/60 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl p-4 text-slate-700 dark:text-slate-200 leading-relaxed text-[0.95rem] whitespace-pre-wrap">{{ $quote->requirements }}</div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-3 pt-2">
            <a href="mailto:{{ $quote->email }}?subject=Re: Quote Request"
               class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Reply via Email
            </a>
            <form data-ajax-form data-reload-table="quotes-table" method="POST"
                  action="{{ route('admin.quotes.update-status', $quote) }}"
                  class="flex items-end gap-2 ml-auto">
                @csrf @method('PUT')
                <x-ui.select name="status" label="Status" :options="['new' => 'New', 'reviewed' => 'Reviewed', 'quoted' => 'Quoted', 'rejected' => 'Rejected']" :value="$quote->status" class="!w-32" />
                <div style="margin-bottom: 1rem;">
                    <button type="submit" class="btn btn-primary shrink-0">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
