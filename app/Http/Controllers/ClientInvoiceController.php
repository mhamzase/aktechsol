<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class ClientInvoiceController extends Controller
{
    /** Show the invoice to the client via secure token link. */
    public function show(string $token)
    {
        $invoice = Invoice::where('token', $token)->with(['project', 'payments' => fn($q) => $q->latest()])->firstOrFail();

        // Auto-flip overdue on open.
        if ($invoice->isOverdue() && $invoice->status !== 'overdue') {
            $invoice->update(['status' => 'overdue']);
        }

        // Payment methods from settings (admin panel).
        $paymentMethods = app(\App\Settings\SiteSettings::class)->payment_methods ?? [];

        return view('invoices.show', compact('invoice', 'paymentMethods'));
    }

    /** Client submits a payment proof (screenshot). */
    public function submitProof(Request $request, string $token)
    {
        $invoice = Invoice::where('token', $token)->firstOrFail();

        if (in_array($invoice->status, ['paid', 'cancelled'])) {
            return response()->json(['message' => 'This invoice is already settled.'], 422);
        }

        $validated = $request->validate([
            'amount'     => 'required|numeric|min:0.01|max:' . max(round($invoice->balance(), 2), 0.01),
            'method'     => 'nullable|string|max:100',
            'reference'  => 'nullable|string|max:255',
            'screenshot' => 'required|image|mimes:jpeg,png,webp|max:5120',
        ]);

        // A proof must never be blocked by a missing method label.
        $validated['method'] = $validated['method'] ?? 'Bank Transfer';

        // Store the exact amount the client typed (rounded to 2 decimals).
        $validated['amount'] = round((float) $validated['amount'], 2);

        $payment = $invoice->payments()->create([
            'amount'    => $validated['amount'],
            'method'    => $validated['method'],
            'reference' => $validated['reference'] ?? null,
            'status'    => 'pending',
        ]);

        // Store the screenshot via Spatie Media Library (single file).
        if ($request->hasFile('screenshot')) {
            $payment->addMediaFromRequest('screenshot')->toMediaCollection('screenshot');
        }

        // Notify admins: system notification + queued email.
        try {
            \App\Support\NotifyAdmins::send(
                'payment',
                'Payment proof submitted',
                "{$invoice->clientName()} submitted \${$payment->amount} for {$invoice->invoice_no}.",
                route('admin.invoices.show', $invoice)
            );

            \App\Support\AdminMail::queue(new \App\Mail\PaymentProofMail($invoice, $payment));
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['message' => 'Payment proof submitted. We will verify it shortly.']);
    }
}
