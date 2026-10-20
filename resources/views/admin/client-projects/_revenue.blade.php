{{-- Drawer form: revenue distribution for a delivered project.
     Percentages are typed per row; amounts + totals update live
     (admin.js) and Save only enables at exactly 100%. --}}
<form data-ajax-form data-reload-table="projects-table" method="POST"
      action="{{ route('admin.client-projects.revenue.update', $project) }}"
      data-revenue-form data-base="{{ $received }}" data-symbol="{{ \App\Support\Money::symbol($project->currency) }}" data-decimals="{{ \App\Support\Money::decimals($project->currency) }}"
      data-reopen-drawer="{{ route('admin.client-projects.show', $project) }}" data-reopen-title="Project Details">
    @csrf
    @method('PUT')

    {{-- Summary: received vs agreed as two clear tiles --}}
    <div class="mb-5">
        <p class="font-bold text-slate-800 dark:text-slate-100 mb-3">{{ $project->title }}</p>
        @php
            $agreed = $project->proposal ? (float) $project->proposal->amount : null;
            $pct = ($agreed && $agreed > 0) ? min(100, (int) round($received / $agreed * 100)) : null;
        @endphp
        <div class="grid {{ $agreed ? 'grid-cols-2' : 'grid-cols-1' }} gap-3 items-stretch">
            <div class="rounded-2xl border border-green-200 dark:border-green-500/25 bg-green-50 dark:bg-green-500/10 p-4">
                <div class="flex items-center gap-1.5 text-[0.65rem] font-bold uppercase tracking-wider text-green-600 dark:text-green-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Received Payment
                </div>
                <p class="text-xl font-black text-green-700 dark:text-green-300 mt-1.5">{{ \App\Support\Money::format($received, $project->currency) }}</p>
                @if($pct !== null)
                    <div class="h-1.5 bg-green-100 dark:bg-green-500/20 rounded-full mt-3 overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="text-[0.7rem] font-semibold text-green-600 dark:text-green-400 mt-1.5">{{ $pct }}% of agreed price collected</p>
                @endif
            </div>
            @if($agreed)
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 p-4">
                    <div class="flex items-center gap-1.5 text-[0.65rem] font-bold uppercase tracking-wider text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Agreed Price
                    </div>
                    <p class="text-xl font-black text-slate-800 dark:text-slate-100 mt-1.5">{{ \App\Support\Money::format($agreed, $project->currency) }}</p>
                    <p class="text-[0.7rem] font-semibold text-slate-400 mt-1.5">Total project value (as per proposal)</p>
                </div>
            @endif
        </div>
        @if($received <= 0)
            <p class="text-xs text-red-500 mt-3">No payment has been received for this project yet. Once an invoice payment is received, its total becomes the amount you can distribute here.</p>
        @elseif($hasShares && abs($distributed - $received) > 0.01)
            <p class="text-xs text-amber-600 dark:text-amber-400 mt-3">Heads up: {{ \App\Support\Money::format($distributed, $project->currency) }} was distributed earlier, but the received total is now {{ \App\Support\Money::format($received, $project->currency) }}. Saving will rebuild the split on the new amount.</p>
        @endif
    </div>

    @if($locked)
        <div class="flex items-start gap-2.5 bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 rounded-xl p-3.5 mb-5">
            <p class="text-xs text-amber-700 dark:text-amber-300 leading-relaxed">
                <strong>Locked:</strong> a share in this distribution has already been paid, so the percentages can no longer be changed.
            </p>
        </div>
    @endif

    {{-- Share rows --}}
    <div class="space-y-2.5 mb-5">
        @foreach($rows as $i => $row)
            <div class="flex items-center gap-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-3.5 py-3" data-revenue-row>
                @if($row['is_company'])
                    <span class="w-9 h-9 rounded-full bg-slate-800 dark:bg-slate-700 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                @else
                    @php $parts = preg_split('/\s+/', trim($row['name'])); $initials = strtoupper(substr($parts[0], 0, 1) . (count($parts) > 1 ? substr(end($parts), 0, 1) : '')); @endphp
                    <span class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white text-xs font-bold flex items-center justify-center shrink-0">{{ $initials }}</span>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">
                        {{ $row['name'] }}
                        @if($row['is_company'])<span class="badge badge-gray ml-1">Company</span>@endif
                    </p>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <input type="hidden" name="shares[{{ $i }}][user_id]" value="{{ $row['user_id'] }}">
                    <input type="number" name="shares[{{ $i }}][percentage]" value="{{ $row['percentage'] }}" min="0" max="100" step="0.01"
                           data-revenue-pct @disabled($locked || $received <= 0)
                           class="w-20 rounded-lg border border-slate-200 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-100 px-2.5 py-2 text-sm font-bold text-right focus:border-[#1d4ed8] focus:outline-none disabled:opacity-50">
                    <span class="text-sm font-bold text-slate-400">%</span>
                </div>
                <span class="w-24 text-right text-sm font-extrabold text-slate-700 dark:text-slate-200 shrink-0" data-revenue-amt>{{ \App\Support\Money::format(0, $project->currency) }}</span>
            </div>
        @endforeach
    </div>

    {{-- Totals --}}
    <div class="mb-5">
        <div class="flex justify-between text-xs font-bold mb-1.5">
            <span class="text-slate-500 dark:text-slate-400">Allocated: <span data-revenue-allocated class="text-slate-800 dark:text-slate-100">0%</span></span>
            <span class="text-slate-500 dark:text-slate-400">Remaining: <span data-revenue-remaining class="text-slate-800 dark:text-slate-100">100%</span></span>
        </div>
        <div class="h-2.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
            <div class="h-full rounded-full transition-all" data-revenue-bar style="width: 0%; background-color: #3b82f6;"></div>
        </div>
        <p class="text-xs text-slate-400 mt-1.5">Percentages must add up to exactly 100%. Amounts are calculated from the received payment.</p>
    </div>

    @if($locked)
        <div class="flex justify-end">
            <button type="button" class="btn btn-secondary" onclick="closeDrawer()">Close</button>
        </div>
    @else
        <x-admin.drawer-footer save-label="Save Distribution" />
    @endif
</form>
