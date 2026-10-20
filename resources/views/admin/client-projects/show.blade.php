{{-- Client project details drawer --}}
<div class="drawer-content">
    <div class="px-6 pb-6 pt-2 space-y-6">

        {{-- Header card --}}
        <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #1d4ed8 0%, #7c3aed 100%);">
            <p class="text-[0.7rem] font-bold uppercase tracking-widest text-blue-200">Client Project</p>
            <h3 class="text-xl font-extrabold mt-1">{{ $project->title }}</h3>
            <p class="text-sm text-blue-100 mt-1">{{ $project->client_name }} · {{ $project->client_email }}</p>
            <div class="mt-4">
                <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                    <span>Progress</span>
                    <span>{{ $project->progress }}%</span>
                </div>
                <div class="h-2.5 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white rounded-full transition-all" style="width: {{ $project->progress }}%"></div>
                </div>
            </div>
        </div>

        {{-- Meta grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Status</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $project->statusLabel() }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Team</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $project->team->count() }} member{{ $project->team->count() === 1 ? '' : 's' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Start Date</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $project->started_at?->format('M d, Y') ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Deadline</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $project->deadline?->format('M d, Y') ?? '—' }}</p>
            </div>
        </div>

        {{-- Project team --}}
        <div>
            <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Project Team</p>
            @forelse($project->team as $member)
                <div class="flex items-center gap-3 py-2.5 {{ !$loop->last ? 'border-b border-slate-100 dark:border-slate-700/60' : '' }}">
                    @php $parts = preg_split('/\s+/', trim($member->name)); $initials = strtoupper(substr($parts[0], 0, 1) . (count($parts) > 1 ? substr(end($parts), 0, 1) : '')); @endphp
                    <span class="w-9 h-9 rounded-full bg-gradient-to-br from-[#1d4ed8] to-[#12307e] text-white text-xs font-bold flex items-center justify-center shrink-0">{{ $initials }}</span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">{{ $member->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $member->email }}</p>
                    </div>
                    <span class="ml-auto badge badge-blue shrink-0">{{ $member->getRoleNames()->first() ?? 'Team Member' }}</span>
                </div>
            @empty
                <p class="text-sm text-slate-400 py-1">No team members assigned yet. Edit the project to assign a team.</p>
            @endforelse
        </div>

        {{-- Revenue distribution (delivered projects with shares saved) --}}
        @if($project->revenueShares->isNotEmpty())
            <div>
                <p class="text-[0.7rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Revenue Distribution</p>
                @foreach($project->revenueShares as $share)
                    <div class="flex items-center gap-3 py-2.5 {{ !$loop->last ? 'border-b border-slate-100 dark:border-slate-700/60' : '' }}">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">
                                {{ $share->member_name }}@if($share->isCompany()) <span class="text-xs font-semibold text-slate-400">(Company)</span>@endif
                            </p>
                            <p class="text-xs text-slate-400">{{ rtrim(rtrim(number_format((float) $share->percentage, 2), '0'), '.') }}% · {{ \App\Support\Money::format($share->amount, $project->currency) }}</p>
                        </div>
                        <div class="ml-auto flex items-center gap-2 shrink-0">
                            @if($share->isPaid())
                                <span class="badge badge-green">Paid · {{ optional($share->paid_at)->format('d M Y') }}</span>
                                @if($proof = $share->getFirstMediaUrl('payment_proof'))
                                    <a href="{{ $proof }}" target="_blank" class="text-xs font-bold text-[#1d4ed8] dark:text-blue-400 hover:underline">View Proof</a>
                                @endif
                            @else
                                <span class="badge badge-amber">Pending</span>
                                <button type="button" onclick="openDrawer('{{ route('admin.client-projects.revenue.paid-form', [$project, $share]) }}', 'Mark Share Paid')"
                                        class="text-xs font-bold text-white bg-green-600 hover:bg-green-700 px-2.5 py-1.5 rounded-lg transition">Mark Paid</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Actions (Distribute Revenue lives in the listing's row actions) --}}
        <div class="flex flex-wrap gap-2.5">
            {{-- Preview + Copy as one split button --}}
            <div class="inline-flex items-stretch rounded-xl overflow-hidden shadow-sm">
                <a href="{{ $project->trackingUrl() }}" target="_blank"
                   class="inline-flex items-center gap-1.5 pl-4 pr-3 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Preview
                </a>
                <button type="button" title="Copy Tracking Link" onclick="navigator.clipboard.writeText('{{ $project->trackingUrl() }}'); window.notify.success('Tracking link copied!')"
                        class="inline-flex items-center px-3 text-white bg-blue-600 hover:bg-blue-700 border-l border-white/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                </button>
            </div>
        </div>

        {{-- Updates timeline --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-extrabold text-slate-800 dark:text-slate-100">Project Updates</h4>
                <button type="button" data-toggle-update-form
                        class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm">
                    <svg data-toggle-icon-plus class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <svg data-toggle-icon-minus class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    <span data-toggle-label>Add Update</span>
                </button>
            </div>

            {{-- Add update form --}}
            <form id="add-update-form" class="hidden bg-slate-50 dark:bg-slate-800/60 rounded-xl p-4 mb-4 space-y-3"
                  data-update-url="{{ route('admin.client-projects.updates.store', $project) }}"
                  data-project-id="{{ $project->id }}">
                @csrf
                <x-ui.input name="title" label="Update Title" required placeholder="Design mockups ready" />
                <x-ui.textarea name="note" label="Note" rows="2" placeholder="Details…" />
                <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-300 cursor-pointer">
                    <input type="checkbox" name="is_client_visible" value="1" checked class="rounded w-4 h-4 accent-blue-600">
                    Visible to client
                </label>
                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary text-sm">Add Update</button>
                    <button type="button" data-toggle-update-form class="btn btn-ghost text-sm">Cancel</button>
                </div>
            </form>

            {{-- Timeline --}}
            <div class="space-y-3" id="updates-timeline">
                @forelse($project->updates as $update)
                <div class="flex gap-3 group">
                    <div class="flex flex-col items-center">
                        <div class="w-3 h-3 rounded-full bg-blue-600 mt-1.5 shrink-0"></div>
                        @if(!$loop->last)<div class="w-0.5 flex-1 bg-slate-200 dark:bg-slate-700"></div>@endif
                    </div>
                    <div class="flex-1 bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                        <div class="flex items-start justify-between gap-2">
                            <p class="font-bold text-sm text-slate-800 dark:text-slate-100">{{ $update->title }}</p>
                            <button type="button" data-delete-update="{{ route('admin.client-projects.updates.destroy', [$project, $update->id]) }}"
                                    class="text-slate-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition shrink-0" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        @if($update->note)
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $update->note }}</p>
                        @endif
                        <p class="text-xs text-slate-400 mt-2 flex items-center gap-1.5 flex-wrap">
                            <span>{{ $update->created_at->format('M d, Y h:i A') }}</span>
                            @if($update->author)<span>· {{ $update->author->name }}</span>@endif
                            @if($update->is_client_visible)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300 rounded-full text-[0.65rem] font-bold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Client visible
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 rounded-full text-[0.65rem] font-bold">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                Internal only
                            </span>
                            @endif
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-slate-400 text-center py-6">No updates yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

