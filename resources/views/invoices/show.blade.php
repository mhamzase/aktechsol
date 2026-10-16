@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_no)

@section('content')
<div class="min-h-screen bg-slate-50 py-6 md:py-10 px-4">
    <div class="max-w-3xl mx-auto space-y-5 md:space-y-6">

        {{-- Hero --}}
        <div class="rounded-3xl p-6 md:p-8 text-white" style="background: linear-gradient(135deg, #1d4ed8 0%, #12307e 100%);">
            <p class="text-xs font-bold uppercase tracking-[0.2em] text-blue-200/70">Invoice {{ $invoice->invoice_no }}</p>
            <h1 class="text-2xl md:text-3xl font-extrabold mt-2">{{ $invoice->title }}</h1>
            <p class="text-blue-200/80 text-sm mt-1.5">Hi {{ $invoice->clientName() }} — here's your invoice.</p>

            <div class="grid grid-cols-3 gap-2 md:gap-3 mt-6">
                <div class="bg-white/10 rounded-2xl p-3 md:p-4">
                    <p class="text-[0.65rem] font-bold uppercase tracking-wider text-blue-200/70">Total</p>
                    <p class="text-base md:text-xl font-extrabold mt-1 break-all">${{ number_format($invoice->amount, 2) }}</p>
                </div>
                <div class="bg-white/10 rounded-2xl p-3 md:p-4">
                    <p class="text-[0.65rem] font-bold uppercase tracking-wider text-blue-200/70">Paid</p>
                    <p class="text-base md:text-xl font-extrabold mt-1 break-all">${{ number_format($invoice->paid_amount, 2) }}</p>
                </div>
                <div class="bg-white/10 rounded-2xl p-3 md:p-4">
                    <p class="text-[0.65rem] font-bold uppercase tracking-wider text-blue-200/70">Balance</p>
                    <p class="text-base md:text-xl font-extrabold mt-1 break-all">${{ number_format($invoice->balance(), 2) }}</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 mt-5 text-sm">
                <span class="inline-block px-3 py-1 bg-white/15 rounded-full text-xs font-bold">{{ $invoice->statusLabel() }}</span>
                @if($invoice->due_date)
                <span class="text-blue-200/80 text-xs">Due: <strong class="text-white">{{ $invoice->due_date->format('M d, Y') }}</strong></span>
                @endif
            </div>
        </div>

        {{-- Payment methods from settings --}}
        @if(!empty($paymentMethods))
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100">
            <h2 class="text-lg font-extrabold text-slate-900 mb-1">Pay Via</h2>
            <p class="text-sm text-slate-400 mb-5">Choose a payment method, then submit your proof below.</p>
            <div class="grid grid-cols-1 gap-4">
                @foreach($paymentMethods as $idx => $pm)
                @if(!empty($pm['method']) || !empty($pm['account_number']))
                <label class="cursor-pointer block">
                    <input type="radio" name="payment_method_select" value="{{ $idx }}" class="peer sr-only" {{ $idx === 0 ? 'checked' : '' }}>
                    <div class="p-4 rounded-2xl border-2 border-slate-200 bg-white peer-checked:border-blue-600 peer-checked:bg-gradient-to-br peer-checked:from-blue-50 peer-checked:to-blue-100/50 peer-checked:shadow-xl peer-checked:shadow-blue-200/50 hover:border-blue-400 hover:shadow-lg transition-all">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-wider mb-1">Payment Method</p>
                                <p class="text-sm font-extrabold text-slate-900">{{ $pm['method'] ?? 'Payment' }}</p>
                            </div>
                            <span class="w-6 h-6 rounded-full border-2 border-slate-300 peer-checked:border-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="w-3 h-3 rounded-full bg-blue-600 scale-0 peer-checked:scale-100 transition-transform"></span>
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-x-5 gap-y-1 text-xs">
                            @if(!empty($pm['account_holder']))
                            <p><span class="text-slate-400 font-medium">Holder:</span> <span class="font-bold text-slate-800">{{ $pm['account_holder'] }}</span></p>
                            @endif
                            @if(!empty($pm['bank_name']))
                            <p><span class="text-slate-400 font-medium">Bank:</span> <span class="font-bold text-slate-800">{{ $pm['bank_name'] }}</span></p>
                            @endif
                        </div>
                        @if(!empty($pm['account_number']))
                        <div class="mt-2 pt-2 border-t border-slate-100 peer-checked:border-blue-200">
                            <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-wider mb-1">Account Number</p>
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-base font-extrabold text-slate-900 tracking-wide select-all">{{ $pm['account_number'] }}</p>
                                <button type="button" data-copy="{{ $pm['account_number'] }}" title="Copy account number"
                                        class="p-2 rounded-xl bg-slate-100 hover:bg-blue-100 text-slate-500 hover:text-blue-600 transition shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>
                        @endif
                        @if(!empty($pm['iban']))
                        <div class="mt-2 flex items-center justify-between gap-2">
                            <p class="text-xs text-slate-400 break-all"><span class="font-bold">IBAN:</span> <span class="select-all">{{ $pm['iban'] }}</span></p>
                            <button type="button" data-copy="{{ $pm['iban'] }}" title="Copy IBAN"
                                    class="p-1.5 rounded-lg bg-slate-100 hover:bg-blue-100 text-slate-400 hover:text-blue-600 transition shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                        @endif
                    </div>
                </label>
                @endif
                @endforeach
            </div>
        </div>
        @endif

        @if($invoice->notes)
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100">
            <h2 class="text-lg font-extrabold text-slate-900 mb-3">Payment Instructions</h2>
            <p class="text-sm text-slate-500 leading-relaxed whitespace-pre-line">{{ $invoice->notes }}</p>
        </div>
        @endif

        {{-- Submit payment proof --}}
        @if(!in_array($invoice->status, ['paid', 'cancelled']) && $invoice->balance() > 0)
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100">
            <h2 class="text-lg font-extrabold text-slate-900 mb-1">Submit Payment Proof</h2>
            <p class="text-sm text-slate-400 mb-5">Paid? Upload your payment screenshot below and we'll verify it.</p>
            <form id="proof-form" action="{{ route('invoices.proof', $invoice->token) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                @csrf
                <div class="md:col-span-12">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Amount ($) <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" step="0.01" min="0.01" max="{{ $invoice->balance() }}" required placeholder="0.00"
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                    <p class="text-xs text-slate-400 mt-1">Max: ${{ number_format($invoice->balance(), 2) }}</p>
                </div>
                <input type="hidden" name="method" id="proof-method" value="">
                <div class="md:col-span-6">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Transaction Reference</label>
                    <input type="text" name="reference" placeholder="TXN-123456"
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
                <div class="md:col-span-6">
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Screenshot <span class="text-red-500">*</span></label>
                    <input type="file" name="screenshot" accept="image/jpeg,image/png,image/webp" required
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 file:font-bold file:text-xs hover:file:bg-blue-100">
                    <p class="text-xs text-slate-400 mt-1">JPG, PNG or WebP — max 5MB.</p>
                </div>
                <div class="md:col-span-12">
                    <button type="submit" class="w-full md:w-auto px-8 py-3.5 text-white font-bold rounded-2xl transition hover:opacity-90"
                            style="background: linear-gradient(135deg, #1d4ed8, #2f6fed);">
                        Submit Proof
                    </button>
                </div>
            </form>
        </div>
        @endif

        {{-- My submitted proofs --}}
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-100">
            <h2 class="text-lg font-extrabold text-slate-900 mb-5">My Payments</h2>
            @forelse($invoice->payments as $payment)
            <div class="flex items-center gap-4 py-4 border-b border-slate-100 last:border-0">
                @if($payment->getFirstMediaUrl('screenshot'))
                <a href="{{ $payment->getFirstMediaUrl('screenshot') }}" target="_blank" class="shrink-0">
                    <img src="{{ $payment->getFirstMediaUrl('screenshot') }}" alt="Proof" class="w-14 h-14 object-cover rounded-xl border border-slate-200">
                </a>
                @endif
                <div class="flex-1">
                    <p class="font-bold text-slate-900">${{ number_format($payment->amount, 2) }}</p>
                    <p class="text-xs text-slate-400">{{ $payment->method }}@if($payment->reference) · {{ $payment->reference }}@endif</p>
                    <p class="text-xs text-slate-400">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                    {{ $payment->status === 'verified' ? 'bg-green-100 text-green-700' : ($payment->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                    {{ ucfirst($payment->status) }}
                </span>
            </div>
            @empty
            <p class="text-slate-400 text-sm text-center py-6">No payments submitted yet.</p>
            @endforelse
        </div>

        <p class="text-center text-xs text-slate-400">
            Questions about this invoice? Reply to our emails and we'll get back to you.
        </p>
    </div>
</div>

@push('scripts')
<script>
// Copy to clipboard (account numbers, IBANs, links).
(function() {
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-copy]');
        if (!btn) return;
        const text = btn.dataset.copy;
        try {
            await navigator.clipboard.writeText(text);
            const original = btn.innerHTML;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>';
            btn.classList.add('!bg-green-100', '!text-green-600');
            setTimeout(() => {
                btn.innerHTML = original;
                btn.classList.remove('!bg-green-100', '!text-green-600');
            }, 1500);
        } catch (err) {
            // Fallback for older browsers.
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            document.execCommand('copy');
            document.body.removeChild(ta);
        }
    });
})();
// Auto-fill method field from selected payment method card.
(function() {
    const methods = @json($paymentMethods);
    const radios = document.querySelectorAll('input[name="payment_method_select"]');
    const methodInput = document.getElementById('proof-method');
    function syncMethod() {
        const selected = document.querySelector('input[name="payment_method_select"]:checked');
        if (selected && methods[selected.value] && methodInput) {
            const pm = methods[selected.value];
            methodInput.value = pm.method || '';
        }
    }
    radios.forEach(r => r.addEventListener('change', syncMethod));
    syncMethod();
})();
// Submit payment proof via AJAX.
(function() {
    const form = document.getElementById('proof-form');
    if (!form) return;
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = form.querySelector('[type="submit"]');
        btn.disabled = true;
        const originalText = btn.textContent;
        btn.textContent = 'Submitting...';
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json().catch(() => ({}));
            if (res.ok) {
                window.notify.success(data.message || 'Proof submitted!');
                setTimeout(() => location.reload(), 1500);
            } else if (res.status === 422 && data.errors) {
                window.notify.error(Object.values(data.errors)[0]?.[0] || 'Please check your input.');
            } else {
                window.notify.error(data.message || 'Something went wrong.');
            }
        } catch (err) {
            window.notify.error('Network error. Please check your connection.');
        } finally {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    });
})();
</script>
@endpush
@endsection
