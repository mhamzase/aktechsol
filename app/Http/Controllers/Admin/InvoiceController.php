<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\ClientProject;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Support\LogActivity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        // Auto-flip overdue invoices (lightweight, on listing open).
        Invoice::needsOverdue()->update(['status' => 'overdue']);

        $query = Invoice::with(['project'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('title', 'like', "%{$s}%")
                ->orWhere('invoice_no', 'like', "%{$s}%")
                ->orWhereHas('project', fn($p) => $p->where('client_name', 'like', "%{$s}%")
                    ->orWhere('client_email', 'like', "%{$s}%")));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = (int) $request->get('per_page', 15);
        $invoices = $query->paginate($perPage)->withQueryString();

        $stats = $this->stats();

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.invoices._rows', ['invoices' => $invoices])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $invoices])->render(),
                'stats'      => "Showing {$invoices->firstItem()}–{$invoices->lastItem()} of {$invoices->total()}",
            ]);
        }

        return view('admin.invoices.index', compact('invoices', 'stats'));
    }

    public function create()
    {
        $invoice = new Invoice();
        $projects = ClientProject::orderBy('title')->get(['id', 'title', 'client_name']);
        return view('admin.invoices.form', compact('invoice', 'projects'));
    }

    public function store(Request $request)
    {
        $invoice = Invoice::create($this->validateData($request));
        LogActivity::record('invoice_created', $invoice, "Created invoice {$invoice->invoice_no}");

        return response()->json(['message' => 'Invoice created.', 'id' => $invoice->id, 'stats' => $this->stats()]);
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['project', 'payments.verifier']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $projects = ClientProject::orderBy('title')->get(['id', 'title', 'client_name']);
        return view('admin.invoices.form', compact('invoice', 'projects'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update($this->validateData($request));
        $invoice->refreshPaymentStatus();
        LogActivity::record('invoice_updated', $invoice, "Updated invoice {$invoice->invoice_no}");

        return response()->json(['message' => 'Invoice updated.', 'stats' => $this->stats()]);
    }

    public function destroy(Invoice $invoice)
    {
        $no = $invoice->invoice_no;
        $invoice->delete();
        LogActivity::record('invoice_deleted', null, "Deleted invoice {$no}");

        return response()->json(['message' => 'Invoice deleted.', 'stats' => $this->stats()]);
    }

    /** Verify a client payment proof -> adds to paid_amount. */
    public function verifyPayment(Invoice $invoice, InvoicePayment $payment)
    {
        if ($payment->invoice_id !== $invoice->id) abort(404);
        if ($payment->status !== 'pending') {
            return response()->json(['message' => 'Payment is not pending.'], 422);
        }

        $payment->update([
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $invoice->increment('paid_amount', $payment->amount);
        $invoice->refresh();
        $invoice->refreshPaymentStatus();

        LogActivity::record('payment_verified', $payment, "Verified payment of {$payment->amount} on {$invoice->invoice_no}");

        // Email the client (queued).
        try {
            if ($invoice->clientEmail()) {
                \Illuminate\Support\Facades\Mail::to($invoice->clientEmail())
                    ->queue(new \App\Mail\PaymentStatusMail($invoice, $payment, true));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['message' => 'Payment verified.']);
    }

    /** Reject a client payment proof. */
    public function rejectPayment(Invoice $invoice, InvoicePayment $payment)
    {
        if ($payment->invoice_id !== $invoice->id) abort(404);
        if ($payment->status !== 'pending') {
            return response()->json(['message' => 'Payment is not pending.'], 422);
        }

        $payment->update([
            'status'      => 'rejected',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        LogActivity::record('payment_rejected', $payment, "Rejected payment on {$invoice->invoice_no}");

        // Email the client (queued).
        try {
            if ($invoice->clientEmail()) {
                \Illuminate\Support\Facades\Mail::to($invoice->clientEmail())
                    ->queue(new \App\Mail\PaymentStatusMail($invoice, $payment, false));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return response()->json(['message' => 'Payment rejected.']);
    }

    /** Record a manual payment (admin). */
    public function addManualPayment(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount'    => 'required|numeric|min:0.01|max:' . $invoice->balance(),
            'method'    => 'required|string|max:100',
            'reference' => 'nullable|string|max:255',
        ]);

        $invoice->payments()->create([
            'amount'      => $validated['amount'],
            'method'      => $validated['method'],
            'reference'   => $validated['reference'] ?? null,
            'status'      => 'verified',
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        $invoice->increment('paid_amount', $validated['amount']);
        $invoice->refresh();
        $invoice->refreshPaymentStatus();

        LogActivity::record('payment_recorded', $invoice, "Manual payment of {$validated['amount']} on {$invoice->invoice_no}");

        return response()->json(['message' => 'Payment recorded.']);
    }

    /** Mark invoice as paid (manual). */
    public function markPaid(Invoice $invoice)
    {
        $invoice->update([
            'paid_amount' => $invoice->amount,
            'status'      => 'paid',
        ]);

        LogActivity::record('invoice_paid', $invoice, "Marked {$invoice->invoice_no} as paid");

        return response()->json(['message' => 'Invoice marked as paid.', 'stats' => $this->stats()]);
    }

    /** Email the invoice to the client. */
    public function sendEmail(Invoice $invoice)
    {
        $email = $invoice->clientEmail();
        if (! $email) {
            return response()->json(['message' => 'No client email on the linked project.'], 422);
        }

        try {
            Mail::to($email)->queue(new InvoiceMail($invoice));
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Failed to queue email.'], 500);
        }

        LogActivity::record('invoice_emailed', $invoice, "Emailed invoice {$invoice->invoice_no} to {$email}");

        return response()->json(['message' => 'Invoice emailed to client.']);
    }

    /** Download the invoice as PDF. */
    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['project', 'payments']);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        $filename = 'invoice-' . Str::slug($invoice->invoice_no) . '.pdf';

        return $pdf->download($filename);
    }

    /** Stats for cards + AJAX refresh. */
    protected function stats(): array
    {
        return [
            'total'          => Invoice::count(),
            'unpaid'         => Invoice::where('status', 'unpaid')->count(),
            'partially_paid' => Invoice::where('status', 'partially_paid')->count(),
            'paid'           => Invoice::where('status', 'paid')->count(),
            'overdue'        => Invoice::where('status', 'overdue')->count(),
        ];
    }

    protected function validateData(Request $request): array
    {
        if ($request->input('client_project_id') === '') {
            $request->merge(['client_project_id' => null]);
        }

        return $request->validate([
            'client_project_id' => 'nullable|exists:client_projects,id',
            'title'             => 'required|string|max:255',
            'amount'            => 'required|numeric|min:0.01',
            'due_date'          => 'nullable|date',
            'status'            => 'required|in:' . implode(',', Invoice::STATUSES),
            'notes'             => 'nullable|string|max:2000',
        ]);
    }
}
