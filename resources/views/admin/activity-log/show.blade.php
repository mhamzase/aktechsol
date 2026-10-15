{{-- Activity log details drawer --}}
<div class="drawer-content p-6">
    <div class="flex items-start gap-3 mb-5">
        <span class="w-11 h-11 rounded-xl bg-blue-100 dark:bg-blue-500/20 text-[#1d4ed8] dark:text-blue-400 flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <div class="min-w-0 flex-1">
            <span class="badge badge-blue">{{ $log->action }}</span>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">{{ $log->created_at->format('d M Y, h:i A') }} · {{ $log->created_at->diffForHumans() }}</p>
        </div>
    </div>

    <div class="space-y-3">
        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">User</p>
            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $log->user->name ?? 'System' }}</p>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Details</p>
            <p class="text-sm text-slate-700 dark:text-slate-300 mt-1 whitespace-pre-wrap">{{ $log->description }}</p>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">IP Address</p>
                <p class="text-sm font-mono text-slate-700 dark:text-slate-300 mt-1">{{ $log->ip_address ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">User Agent</p>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 break-all">{{ \Illuminate\Support\Str::limit($log->user_agent ?? '—', 60) }}</p>
            </div>
        </div>
    </div>
</div>
