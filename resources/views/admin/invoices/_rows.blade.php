{{-- Row partial for the invoices AJAX table. --}}
@php
    $statusBadges = [
        'unpaid' => 'badge-red', 'partially_paid' => 'badge-amber', 'paid' => 'badge-green',
        'overdue' => 'badge-orange', 'cancelled' => 'badge-gray',
    ];
@endphp
@forelse ($invoices as $invoice)
    <tr>
        <td data-label="Invoice">
            <p class="font-bold text-slate-800 dark:text-slate-100 whitespace-nowrap">{{ $invoice->invoice_no }}</p>
            <p class="text-xs text-slate-400">{{ $invoice->created_at->format('M d, Y') }}</p>
        </td>
        <td data-label="Title">
            <p class="font-medium text-slate-800 dark:text-slate-100">{{ $invoice->title }}</p>
            @if($invoice->project)
            <p class="text-xs text-slate-400">{{ $invoice->project->title }}</p>
            @endif
        </td>
        <td data-label="Client">
            <p class="font-medium text-slate-800 dark:text-slate-100">{{ $invoice->clientName() }}</p>
            <p class="text-xs text-slate-400">{{ $invoice->clientEmail() ?? '—' }}</p>
        </td>
        <td data-label="Amount" class="font-semibold text-slate-800 dark:text-slate-100 whitespace-nowrap">{{ \App\Support\Money::format($invoice->amount, $invoice->currency) }}</td>
        <td data-label="Paid" class="whitespace-nowrap">
            <p class="font-semibold text-green-600">{{ \App\Support\Money::format($invoice->paid_amount, $invoice->currency) }}</p>
            @if($invoice->balance() > 0)
            <p class="text-xs text-slate-400">Bal: {{ \App\Support\Money::format($invoice->balance(), $invoice->currency) }}</p>
            @endif
        </td>
        <td data-label="Due" class="text-slate-500 whitespace-nowrap">{{ $invoice->due_date?->format('M d, Y') ?? '—' }}</td>
        <td data-label="Status"><span class="badge {{ $statusBadges[$invoice->status] ?? 'badge-gray' }}">{{ $invoice->statusLabel() }}</span></td>
        <td data-label="Actions" class="no-label whitespace-nowrap">
            <x-admin.row-actions
                :viewUrl="route('admin.invoices.show', $invoice)"
                viewTitle="Invoice Details"
                :editUrl="route('admin.invoices.edit', $invoice)"
                editTitle="Edit Invoice"
                :editWide="true"
                :deleteUrl="route('admin.invoices.destroy', $invoice)"
                tableId="invoices-table" />
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-10 text-slate-400">
            <p class="font-semibold">No invoices found.</p>
            <p class="text-sm mt-1">Create your first invoice to get started.</p>
        </td>
    </tr>
@endforelse
