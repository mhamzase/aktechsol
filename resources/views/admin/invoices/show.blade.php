{{-- Invoice details drawer --}}
<div class="drawer-content">
    <div class="px-6 pb-6 pt-2 space-y-6">

        {{-- Header card --}}
        <div class="rounded-2xl p-5 text-white" style="background: linear-gradient(135deg, #1d4ed8 0%, #7c3aed 100%);">
            <p class="text-[0.7rem] font-bold uppercase tracking-widest text-blue-200">Invoice {{ $invoice->invoice_no }}</p>
            <h3 class="text-xl font-extrabold mt-1">{{ $invoice->title }}</h3>
            <p class="text-sm text-blue-100 mt-1">{{ $invoice->clientName() }}@if($invoice->clientEmail()) · {{ $invoice->clientEmail() }}@endif</p>
            <div class="grid grid-cols-3 gap-3 mt-4">
                <div class="bg-white/15 rounded-xl p-3">
                    <p class="text-[0.65rem] font-bold uppercase tracking-wider text-blue-100">Total</p>
                    <p class="text-lg font-extrabold">${{ number_format($invoice->amount, 2) }}</p>
                </div>
                <div class="bg-white/15 rounded-xl p-3">
                    <p class="text-[0.65rem] font-bold uppercase tracking-wider text-blue-100">Paid</p>
                    <p class="text-lg font-extrabold">${{ number_format($invoice->paid_amount, 2) }}</p>
                </div>
                <div class="bg-white/15 rounded-xl p-3">
                    <p class="text-[0.65rem] font-bold uppercase tracking-wider text-blue-100">Balance</p>
                    <p class="text-lg font-extrabold">${{ number_format($invoice->balance(), 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Meta grid --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Status</p>
                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 mt-1">{{ $invoice->statusLabel() }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Due Date</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $invoice->due_date?->format('M d, Y') ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Project</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $invoice->project?->title ?? '—' }}</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-3.5">
                <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider">Created</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 mt-1">{{ $invoice->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-2.5">
            <button type="button" data-invoice-email data-url="{{ route('admin.invoices.send-email', $invoice) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Email Client
            </button>
            <button type="button" onclick="navigator.clipboard.writeText('{{ $invoice->invoiceUrl() }}'); window.notify.success('Invoice link copied!')"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700/60 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                Copy Client Link
            </button>
            <a href="{{ route('admin.invoices.pdf', $invoice) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-700/60 rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Download PDF
            </a>
            @if(!in_array($invoice->status, ['paid', 'cancelled']))
            <button type="button" data-invoice-mark-paid data-url="{{ route('admin.invoices.mark-paid', $invoice) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-sm font-bold text-white bg-green-600 rounded-xl hover:bg-green-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mark as Paid
            </button>
            @endif
        </div>

        @if($invoice->notes)
        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-4">
            <p class="text-[0.7rem] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Notes</p>
            <p class="text-sm text-slate-600 dark:text-slate-300 whitespace-pre-line">{{ $invoice->notes }}</p>
        </div>
        @endif

        {{-- Payment proofs --}}
        <div>
            <h4 class="font-extrabold text-slate-800 dark:text-slate-100 mb-3">Payment Proofs ({{ $invoice->payments->count() }})</h4>
            <div class="space-y-3">
                @forelse($invoice->payments as $payment)
                <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-4 flex flex-wrap items-center gap-3">
                    @if($payment->getFirstMediaUrl('screenshot'))
                    <a href="{{ $payment->getFirstMediaUrl('screenshot') }}" target="_blank" class="shrink-0">
                        <img src="{{ $payment->getFirstMediaUrl('screenshot') }}" alt="Proof" class="w-16 h-16 object-cover rounded-lg border border-slate-200 dark:border-slate-600">
                    </a>
                    @endif
                    <div class="flex-1 min-w-[140px]">
                        <p class="font-bold text-slate-800 dark:text-slate-100">${{ number_format($payment->amount, 2) }}</p>
                        <p class="text-xs text-slate-400">{{ $payment->method }}@if($payment->reference) · {{ $payment->reference }}@endif</p>
                        <p class="text-xs text-slate-400">{{ $payment->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <span class="badge {{ $payment->status === 'verified' ? 'badge-green' : ($payment->status === 'rejected' ? 'badge-red' : 'badge-amber') }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                    @if($payment->status === 'pending')
                    <div class="flex gap-2">
                        <button type="button" data-verify-payment data-url="{{ route('admin.invoices.payments.verify', [$invoice, $payment]) }}"
                                class="px-3 py-1.5 text-xs font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 transition">Verify</button>
                        <button type="button" data-reject-payment data-url="{{ route('admin.invoices.payments.reject', [$invoice, $payment]) }}"
                                class="px-3 py-1.5 text-xs font-bold text-white bg-red-600 rounded-lg hover:bg-red-700 transition">Reject</button>
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-sm text-slate-400 text-center py-6 bg-slate-50 dark:bg-slate-800/60 rounded-xl">No payment proofs yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Manual payment form --}}
        @if(!in_array($invoice->status, ['paid', 'cancelled']) && $invoice->balance() > 0)
        <div class="bg-slate-50 dark:bg-slate-800/60 rounded-xl p-4">
            <h4 class="font-extrabold text-slate-800 dark:text-slate-100 mb-3">Record Manual Payment</h4>
            <form data-manual-payment data-url="{{ route('admin.invoices.payments.manual', $invoice) }}" class="grid grid-cols-1 md:grid-cols-12 gap-3">
                @csrf
                <div class="md:col-span-4">
                    <x-ui.input name="amount" type="number" step="0.01" min="0.01" max="{{ $invoice->balance() }}" label="Amount ($)" required placeholder="0.00" />
                </div>
                <div class="md:col-span-4">
                    <x-ui.input name="method" label="Method" required placeholder="Bank transfer" />
                </div>
                <div class="md:col-span-4">
                    <x-ui.input name="reference" label="Reference" placeholder="TXN-123" />
                </div>
                <div class="md:col-span-12">
                    <button type="submit" class="btn btn-primary text-sm">Record Payment</button>
                </div>
            </form>
        </div>
        @endif
    </div>
</div>
