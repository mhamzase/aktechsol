{{-- Job application details drawer --}}
<div class="drawer-content">
    {{-- Candidate header --}}
    <div class="flex items-center gap-4 p-6 pb-5 border-b border-slate-100 dark:border-slate-700">
        <span class="w-12 h-12 rounded-full bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white flex items-center justify-center font-bold text-lg shrink-0">
            {{ strtoupper(substr($application->name, 0, 1)) }}
        </span>
        <div class="min-w-0">
            <p class="font-bold text-slate-900 dark:text-slate-100 truncate">{{ $application->name }}</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ $application->email }}</p>
        </div>
        @php
            $statusStyles = [
                'new' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                'reviewed' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                'shortlisted' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
            ];
        @endphp
        <span class="ml-auto shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $statusStyles[$application->status] ?? 'bg-slate-100 text-slate-600' }}">
            {{ ucfirst($application->status) }}
        </span>
    </div>

    <div class="p-6 space-y-5">
        {{-- Meta grid --}}
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Applied For</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $application->job?->title ?? '—' }}</p>
                @if($application->job)<p class="text-xs text-slate-400 dark:text-slate-500 capitalize">{{ $application->job->type }} · {{ $application->job->location_type }}</p>@endif
            </div>
            @if($application->phone)
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Phone</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $application->phone }}</p>
            </div>
            @endif
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Portfolio</p>
                @if($application->portfolio_url)
                    <a href="{{ $application->portfolio_url }}" target="_blank" rel="noopener" class="text-sm font-semibold text-[#1d4ed8] dark:text-blue-400 hover:underline break-all">{{ $application->portfolio_url }}</a>
                @else
                    <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">—</p>
                @endif
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Applied</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $application->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500">{{ $application->created_at->format('h:i A') }} · {{ $application->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Resume / CV --}}
        @php $resumeUrl = $application->getFirstMediaUrl('resume'); @endphp
        @if($resumeUrl)
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Resume / CV</p>
            <a href="{{ $resumeUrl }}" target="_blank" rel="noopener" class="flex items-center gap-3 bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700 rounded-xl p-4 hover:border-blue-200 transition">
                <svg class="w-8 h-8 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>
                    <span class="block text-sm font-bold text-slate-800 dark:text-slate-100">View / Download CV</span>
                    <span class="block text-xs text-slate-400">PDF document uploaded by the candidate</span>
                </span>
            </a>
        </div>
        @endif

        {{-- Cover note --}}
        @if($application->cover_note)
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Cover Note</p>
            <div class="bg-blue-50/60 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl p-4 text-slate-700 dark:text-slate-200 leading-relaxed text-[0.95rem] whitespace-pre-wrap">{{ $application->cover_note }}</div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-3 pt-2">
            <a href="mailto:{{ $application->email }}?subject=Your application at {{ $siteSettings->site_name ?: 'AK Tech SOL' }}"
               class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Reply via Email
            </a>
            <form data-ajax-form data-reload-table="job-applications-table" method="POST"
                  action="{{ route('admin.job-applications.update-status', $application) }}"
                  class="flex items-end gap-2 ml-auto">
                @csrf @method('PUT')
                <x-ui.select name="status" label="Status" :options="['new' => 'New', 'reviewed' => 'Reviewed', 'shortlisted' => 'Shortlisted', 'rejected' => 'Rejected']" :value="$application->status" class="!w-36" />
                <div style="margin-bottom: 1rem;">
                    <button type="submit" class="btn btn-primary shrink-0">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
