@extends('layouts.app')

@section('title', $proposal->title . ' | Proposal')
@section('meta_description', 'Review your proposal from ' . ($siteSettings->site_name ?? 'AK Tech SOL') . '.')

@section('content')
    <section class="py-12 sm:py-16 bg-slate-50/60 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6">
            {{-- Header card --}}
            <div class="bg-gradient-to-r from-[#1d4ed8] to-[#12307e] rounded-3xl p-8 sm:p-10 text-white mb-6">
                <p class="text-blue-200/70 text-xs font-bold uppercase tracking-widest mb-2">Proposal</p>
                <h1 class="text-2xl sm:text-3xl font-extrabold">{{ $proposal->title }}</h1>
                <p class="text-blue-100/80 text-sm mt-2">Prepared for {{ $proposal->client_name }}</p>
                <div class="flex flex-wrap gap-4 mt-5 text-sm">
                    <div class="bg-white/10 rounded-xl px-4 py-2.5">
                        <p class="text-blue-200/70 text-xs">Total Investment</p>
                        <p class="font-extrabold text-lg">${{ number_format($proposal->amount, 2) }}</p>
                    </div>
                    @if($proposal->timeline)
                    <div class="bg-white/10 rounded-xl px-4 py-2.5">
                        <p class="text-blue-200/70 text-xs">Timeline</p>
                        <p class="font-bold">{{ $proposal->timeline }}</p>
                    </div>
                    @endif
                    @if($proposal->valid_until)
                    <div class="bg-white/10 rounded-xl px-4 py-2.5">
                        <p class="text-blue-200/70 text-xs">Valid Until</p>
                        <p class="font-bold">{{ $proposal->valid_until->format('M d, Y') }}</p>
                    </div>
                    @endif
                </div>
                <div class="mt-5">
                    <a href="{{ route('proposals.pdf.download', $proposal->token) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/15 text-white text-sm font-bold rounded-xl hover:bg-white/25 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Download PDF
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-5 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-medium">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-5 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-medium">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Scope --}}
            <div class="bg-white rounded-3xl p-8 border border-slate-100 mb-6">
                <h2 class="text-lg font-extrabold text-slate-900 mb-4">Project Scope</h2>
                <div class="prose prose-sm max-w-none text-slate-600">{!! $proposal->scope !!}</div>
            </div>

            {{-- Milestones --}}
            @if($proposal->milestones)
            <div class="bg-white rounded-3xl p-8 border border-slate-100 mb-6">
                <h2 class="text-lg font-extrabold text-slate-900 mb-4">Payment Milestones</h2>
                <div class="space-y-3">
                    @foreach($proposal->milestones as $i => $m)
                    <div class="flex items-center justify-between bg-slate-50 rounded-2xl px-5 py-4">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-xl bg-[#1d4ed8]/10 text-[#1d4ed8] font-bold text-sm flex items-center justify-center">{{ $i + 1 }}</span>
                            <span class="font-semibold text-slate-800">{{ $m['title'] }}</span>
                        </div>
                        <span class="font-extrabold text-slate-900">${{ number_format($m['amount'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Response --}}
            @if(in_array($proposal->status, ['sent', 'changes_requested']))
            <div class="bg-white rounded-3xl p-8 border border-slate-100">
                <h2 class="text-lg font-extrabold text-slate-900 mb-2">Your Response</h2>
                <p class="text-sm text-slate-500 mb-6">Please review the proposal above and let us know your decision.</p>
                <form id="proposal-respond-form" method="POST" action="{{ route('proposals.respond', $proposal->token) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Your Message <span class="text-red-500">*</span></label>
                        <textarea name="client_note" rows="3" required placeholder="Please share your thoughts…"
                                  class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none"></textarea>
                        <p class="text-xs text-slate-400 mt-1.5">Minimum 10 characters.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="button" data-respond="accepted"
                                class="respond-btn px-8 py-3.5 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition disabled:opacity-60">
                            Accept Proposal
                        </button>
                        <button type="button" data-respond="changes_requested"
                                class="respond-btn px-8 py-3.5 bg-amber-500 text-white font-bold rounded-2xl hover:bg-amber-600 transition disabled:opacity-60">
                            Request Changes
                        </button>
                        <button type="button" data-respond="rejected"
                                class="respond-btn px-8 py-3.5 bg-slate-200 text-slate-700 font-bold rounded-2xl hover:bg-slate-300 transition disabled:opacity-60">
                            Decline
                        </button>
                    </div>
                </form>
            </div>
            @push('scripts')
            {{-- Branded confirm modal for proposal responses --}}
            <div id="respond-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-confirm-cancel></div>
                <div class="relative bg-white rounded-3xl shadow-2xl max-w-sm w-full p-7 text-center">
                    <div id="respond-confirm-icon" class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center mb-4"></div>
                    <h3 id="respond-confirm-title" class="text-lg font-extrabold text-slate-900"></h3>
                    <p id="respond-confirm-msg" class="text-sm text-slate-500 mt-2 mb-6"></p>
                    <div class="flex gap-3">
                        <button type="button" data-confirm-cancel
                                class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition">
                            Cancel
                        </button>
                        <button type="button" id="respond-confirm-btn"
                                class="flex-1 px-4 py-3 text-white font-bold rounded-2xl transition">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
            <script>
            (function() {
                const form = document.getElementById('proposal-respond-form');
                if (!form) return;
                const buttons = form.querySelectorAll('.respond-btn');
                const modal = document.getElementById('respond-confirm-modal');
                const noteInput = form.querySelector('[name="client_note"]');

                const configs = {
                    accepted: {
                        title: 'Accept Proposal?',
                        msg: 'You are accepting this proposal. Our team will contact you to get started.',
                        iconBg: 'bg-green-100', iconColor: 'text-green-600',
                        icon: '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>',
                        btnClass: 'bg-green-600 hover:bg-green-700',
                    },
                    changes_requested: {
                        title: 'Request Changes?',
                        msg: 'We will review your feedback and send an updated proposal.',
                        iconBg: 'bg-amber-100', iconColor: 'text-amber-600',
                        icon: '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
                        btnClass: 'bg-amber-500 hover:bg-amber-600',
                    },
                    rejected: {
                        title: 'Decline Proposal?',
                        msg: 'Are you sure you want to decline? This cannot be undone.',
                        iconBg: 'bg-red-100', iconColor: 'text-red-600',
                        icon: '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                        btnClass: 'bg-red-600 hover:bg-red-700',
                    },
                };

                let pendingAction = null;

                function showError(msg) {
                    window.notify.error(msg);
                    noteInput.classList.add('!border-red-400', '!ring-2', '!ring-red-100');
                    noteInput.focus();
                    setTimeout(() => noteInput.classList.remove('!border-red-400', '!ring-2', '!ring-red-100'), 3000);
                }

                function openConfirm(action) {
                    const cfg = configs[action];
                    pendingAction = action;
                    document.getElementById('respond-confirm-title').textContent = cfg.title;
                    document.getElementById('respond-confirm-msg').textContent = cfg.msg;
                    const iconBox = document.getElementById('respond-confirm-icon');
                    iconBox.className = `w-14 h-14 mx-auto rounded-2xl flex items-center justify-center mb-4 ${cfg.iconBg} ${cfg.iconColor}`;
                    iconBox.innerHTML = cfg.icon;
                    const confirmBtn = document.getElementById('respond-confirm-btn');
                    confirmBtn.className = `flex-1 px-4 py-3 text-white font-bold rounded-2xl transition ${cfg.btnClass}`;
                    confirmBtn.textContent = cfg.title.replace('?', '');
                    modal.classList.remove('hidden');
                }

                function closeConfirm() {
                    modal.classList.add('hidden');
                    pendingAction = null;
                }

                modal.querySelectorAll('[data-confirm-cancel]').forEach(el =>
                    el.addEventListener('click', closeConfirm));
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeConfirm();
                });

                async function submitResponse(action, btn) {
                    buttons.forEach(b => b.disabled = true);
                    const originalText = btn.textContent;
                    btn.textContent = 'Sending...';
                    try {
                        const formData = new FormData(form);
                        formData.set('action', action);
                        const res = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        });
                        let data = {};
                        try { data = await res.json(); } catch (e) {}
                        if (res.ok) {
                            window.notify.success(data.message || 'Response recorded!');
                            setTimeout(() => location.reload(), 1500);
                        } else if (res.status === 422 && data.errors) {
                            const firstError = Object.values(data.errors)[0]?.[0];
                            showError(firstError || 'Please check your input.');
                        } else {
                            window.notify.error(data.message || 'Something went wrong. Please try again.');
                        }
                    } catch (err) {
                        window.notify.error('Network error. Please check your connection.');
                    } finally {
                        buttons.forEach(b => b.disabled = false);
                        btn.textContent = originalText;
                    }
                }

                document.getElementById('respond-confirm-btn').addEventListener('click', () => {
                    if (!pendingAction) return;
                    const btn = form.querySelector(`[data-respond="${pendingAction}"]`);
                    closeConfirm();
                    submitResponse(pendingAction, btn);
                });

                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        // Step 1: validate message first.
                        const note = (noteInput.value || '').trim();
                        if (note.length < 10) {
                            showError('Please write your message first (minimum 10 characters).');
                            return;
                        }
                        // Step 2: branded confirmation.
                        openConfirm(btn.dataset.respond);
                    });
                });

                // Live-clear error styling.
                noteInput.addEventListener('input', () => {
                    noteInput.classList.remove('!border-red-400', '!ring-2', '!ring-red-100');
                });
            })();
            </script>
            @endpush
            @else
            <div class="bg-white rounded-3xl p-8 border border-slate-100 text-center">
                @php
                    $statusMsg = [
                        'accepted' => ['Thank you!', 'You have accepted this proposal. Our team will be in touch shortly.', 'green'],
                        'rejected' => ['Proposal declined', 'You have declined this proposal.', 'red'],
                        'changes_requested' => ['Changes requested', 'We have received your feedback and will update the proposal.', 'amber'],
                        'expired' => ['Proposal expired', 'This proposal is no longer valid. Please contact us for a new one.', 'slate'],
                    ][$proposal->status] ?? null;
                @endphp
                @if($statusMsg)
                    <h2 class="text-xl font-extrabold text-slate-900">{{ $statusMsg[0] }}</h2>
                    <p class="text-slate-500 text-sm mt-2">{{ $statusMsg[1] }}</p>
                @endif
            </div>
            @endif
        </div>
    </section>
@endsection
