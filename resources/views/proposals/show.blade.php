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
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Note (optional)</label>
                        <textarea name="client_note" rows="3" placeholder="Any comments or questions…"
                                  class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-[#1d4ed8] focus:ring-2 focus:ring-[#1d4ed8]/20 focus:outline-none"></textarea>
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
            <script>
            (function() {
                const form = document.getElementById('proposal-respond-form');
                if (!form) return;
                const buttons = form.querySelectorAll('.respond-btn');

                buttons.forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const action = btn.dataset.respond;
                        if (action === 'rejected' && !confirm('Are you sure you want to decline this proposal?')) return;

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
                                window.notify.error(firstError || 'Please check your input.');
                            } else {
                                window.notify.error(data.message || 'Something went wrong. Please try again.');
                            }
                        } catch (err) {
                            window.notify.error('Network error. Please check your connection.');
                        } finally {
                            buttons.forEach(b => b.disabled = false);
                            btn.textContent = originalText;
                        }
                    });
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
