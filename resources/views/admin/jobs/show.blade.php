{{-- Job listing details drawer — rich HTML rendered like the careers page --}}
<div class="drawer-content">
    {{-- Header --}}
    <div class="p-6 pb-5 border-b border-slate-100 dark:border-slate-700">
        <div class="flex flex-wrap items-center gap-2 mb-2.5">
            <span class="badge {{ $job->type === 'internship' ? 'badge-purple' : 'badge-blue' }}">{{ $job->type === 'internship' ? 'Internship' : 'Job' }}</span>
            <span class="badge badge-gray capitalize">{{ $job->location_type }}</span>
            <span class="badge {{ $job->is_open ? 'badge-green' : 'badge-red' }}">{{ $job->is_open ? 'Open' : 'Closed' }}</span>
        </div>
        <p class="font-bold text-lg text-slate-900 dark:text-slate-100">{{ $job->title }}</p>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
            {{ $job->applications_count }} application(s) · Posted {{ $job->created_at->format('M d, Y') }} ·
            <a href="{{ route('careers.show', $job->slug) }}" target="_blank" rel="noopener" class="text-[#1d4ed8] dark:text-blue-400 font-semibold hover:underline">View on website</a>
        </p>
    </div>

    <div class="p-6 space-y-6">
        {{-- Description (rich HTML) --}}
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Description</p>
            <div class="prose prose-slate dark:prose-invert max-w-none text-[0.95rem] leading-relaxed bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700 rounded-xl p-4">
                {!! $job->description !!}
            </div>
        </div>

        {{-- Requirements (rich HTML) --}}
        @if($job->requirements)
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-2">Requirements</p>
            <div class="prose prose-slate dark:prose-invert max-w-none text-[0.95rem] leading-relaxed bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-700 rounded-xl p-4">
                {!! $job->requirements !!}
            </div>
        </div>
        @endif
    </div>
</div>
