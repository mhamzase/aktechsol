{{-- Demo booking details drawer --}}
<div class="drawer-content">
    {{-- Booker header --}}
    <div class="flex items-center gap-4 p-6 pb-5 border-b border-slate-100 dark:border-slate-700">
        <span class="w-12 h-12 rounded-full bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white flex items-center justify-center font-bold text-lg shrink-0">
            {{ strtoupper(substr($booking->name, 0, 1)) }}
        </span>
        <div class="min-w-0">
            <p class="font-bold text-slate-900 dark:text-slate-100 truncate">{{ $booking->name }}</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 truncate">{{ $booking->email }}</p>
        </div>
        @php
            $statusStyles = [
                'new' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
                'contacted' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                'scheduled' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400',
                'done' => 'bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400',
                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400',
            ];
        @endphp
        <span class="ml-auto shrink-0 text-xs font-bold px-3 py-1 rounded-full {{ $statusStyles[$booking->status] ?? 'bg-slate-100 text-slate-600' }}">
            {{ ucfirst($booking->status) }}
        </span>
    </div>

    <div class="p-6 space-y-5">
        {{-- Meta grid --}}
        <div class="grid grid-cols-2 gap-4">
            @if($booking->phone)
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Phone</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $booking->phone }}</p>
            </div>
            @endif
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Service</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $booking->service ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Preferred Date</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $booking->preferred_date ? $booking->preferred_date->format('M d, Y') : '—' }}</p>
                @if($booking->preferred_date)<p class="text-xs text-slate-400 dark:text-slate-500">{{ $booking->preferred_date->format('l') }}</p>@endif
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Preferred Time</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $booking->preferred_time ?? '—' }}</p>
                @if($booking->preferred_time)<p class="text-xs text-slate-400 dark:text-slate-500">Pakistan Standard Time</p>@endif
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Received</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $booking->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-slate-400 dark:text-slate-500">{{ $booking->created_at->format('h:i A') }} · {{ $booking->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- Message --}}
        @if($booking->message)
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Message</p>
            <div class="bg-blue-50/60 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20 rounded-xl p-4 text-slate-700 dark:text-slate-200 leading-relaxed text-[0.95rem] whitespace-pre-wrap">{{ $booking->message }}</div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-3 pt-2">
            <a href="mailto:{{ $booking->email }}?subject=Your Demo with {{ $siteSettings->site_name ?: 'AK Tech SOL' }}"
               class="btn btn-secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Reply via Email
            </a>
            <form data-ajax-form data-reload-table="demo-bookings-table" method="POST"
                  action="{{ route('admin.demo-bookings.update-status', $booking) }}"
                  class="flex items-end gap-2 ml-auto">
                @csrf @method('PUT')
                <x-ui.select name="status" label="Status" :options="['new' => 'New', 'contacted' => 'Contacted', 'scheduled' => 'Scheduled', 'done' => 'Done', 'cancelled' => 'Cancelled']" :value="$booking->status" class="!w-36" />
                <div style="margin-bottom: 1rem;">
                    <button type="submit" class="btn btn-primary shrink-0">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
