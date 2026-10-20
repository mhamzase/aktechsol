<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ProposalSentMail;
use App\Models\Proposal;
use App\Models\QuoteRequest;
use App\Support\LogActivity;
use App\Support\NotifyAdmins;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        // Auto-expire stale proposals (no cron dependency for the listing).
        Proposal::whereNotIn('status', ['accepted', 'rejected', 'expired'])
            ->whereNotNull('valid_until')
            ->whereDate('valid_until', '<', today())
            ->update(['status' => 'expired']);

        $query = Proposal::query()->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $proposals = $query->paginate($request->get('per_page', 10));

        $stats = [
            'draft'    => Proposal::where('status', 'draft')->count(),
            'sent'     => Proposal::where('status', 'sent')->count(),
            'accepted' => Proposal::where('status', 'accepted')->count(),
            'total'    => Proposal::count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.proposals._rows', ['proposals' => $proposals])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $proposals])->render(),
                'stats'      => "Showing {$proposals->firstItem()}–{$proposals->lastItem()} of {$proposals->total()}",
            ]);
        }

        return view('admin.proposals.index', compact('proposals', 'stats'));
    }

    public function create()
    {
        $quotes = QuoteRequest::where('status', 'quoted')->latest()->get(['id', 'name', 'email', 'subject']);
        $proposal = new Proposal();
        $proposal->currency = \App\Support\Money::code(app(\App\Settings\SiteSettings::class)->default_currency);
        return view('admin.proposals.form', ['proposal' => $proposal, 'quotes' => $quotes]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quote_request_id' => 'nullable|exists:quote_requests,id',
            'title'            => 'required|string|max:255',
            'client_name'      => 'required|string|max:255',
            'client_email'     => 'required|email|max:255',
            'scope'            => 'required|string',
            'timeline'         => 'nullable|string|max:255',
            'amount'           => 'required|numeric|min:0',
            'currency'         => 'nullable|in:PKR,USD',
            'valid_until'      => 'nullable|date|after:today',
            'milestones'       => 'nullable|array',
            'milestones.*.title'  => 'required_with:milestones|string|max:255',
            'milestones.*.amount' => 'required_with:milestones|numeric|min:0',
        ]);

        $validated['currency'] = \App\Support\Money::code($validated['currency'] ?? null);
        $proposal = Proposal::create($validated);
        LogActivity::record('proposal_created', $proposal, "Created proposal: {$proposal->title}");

        // Email the proposal to the client (queued).
        try {
            Mail::to($proposal->client_email)->queue(new ProposalSentMail($proposal));
        } catch (\Throwable $e) {
            report($e);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Proposal created.', 'id' => $proposal->id]);
        }

        return redirect()->route('admin.proposals.index')->with('success', 'Proposal created.');
    }

    public function show(Proposal $proposal)
    {
        if (request()->ajax() || request()->expectsJson()) {
            return view('admin.proposals.show', compact('proposal'));
        }

        return redirect()->route('admin.proposals.index', ['view' => $proposal->id]);
    }

    public function edit(Proposal $proposal)
    {
        $quotes = QuoteRequest::where('status', 'quoted')->latest()->get(['id', 'name', 'email', 'subject']);
        return view('admin.proposals.form', compact('proposal', 'quotes'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'quote_request_id' => 'nullable|exists:quote_requests,id',
            'title'            => 'required|string|max:255',
            'client_name'      => 'required|string|max:255',
            'client_email'     => 'required|email|max:255',
            'scope'            => 'required|string',
            'timeline'         => 'nullable|string|max:255',
            'amount'           => 'required|numeric|min:0',
            'currency'         => 'nullable|in:PKR,USD',
            'valid_until'      => 'nullable|date|after:today',
            'milestones'       => 'nullable|array',
            'milestones.*.title'  => 'required_with:milestones|string|max:255',
            'milestones.*.amount' => 'required_with:milestones|numeric|min:0',
        ]);

        $validated['currency'] = \App\Support\Money::code($validated['currency'] ?? $proposal->currency);
        $proposal->update($validated);
        LogActivity::record('proposal_updated', $proposal, "Updated proposal: {$proposal->title}");

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Proposal updated.']);
        }

        return redirect()->route('admin.proposals.index')->with('success', 'Proposal updated.');
    }

    /** Mark as sent (generates the client link). */
    public function send(Request $request, Proposal $proposal)
    {
        $proposal->update(['status' => 'sent', 'sent_at' => now()]);
        LogActivity::record('proposal_sent', $proposal, "Sent proposal: {$proposal->title}");

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Proposal marked as sent.',
                'public_url' => $proposal->publicUrl(),
            ]);
        }

        return back()->with('success', 'Proposal marked as sent.');
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        LogActivity::record('proposal_deleted', null, "Deleted proposal: {$proposal->title}");

        if (request()->ajax()) {
            return response()->json(['message' => 'Proposal deleted.']);
        }

        return redirect()->route('admin.proposals.index')->with('success', 'Proposal deleted.');
    }

    /** Download proposal as PDF. */
    public function pdf(Proposal $proposal)
    {
        $pdf = Pdf::loadView('proposals.pdf', compact('proposal'));
        return $pdf->download(Str::slug($proposal->title) . '.pdf');
    }

    /** Resend the proposal email to the client. */
    public function resend(Proposal $proposal)
    {
        // Accepted proposals are final — no resends.
        if ($proposal->status === 'accepted') {
            return response()->json(['message' => 'This proposal has already been accepted.'], 422);
        }

        if (! $proposal->client_email) {
            return response()->json(['message' => 'No client email on this proposal.'], 422);
        }

        try {
            Mail::to($proposal->client_email)->queue(new ProposalSentMail($proposal));
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Failed to queue email.'], 500);
        }

        LogActivity::record('proposal_resent', $proposal, "Resent proposal email: {$proposal->title}");

        return response()->json(['message' => 'Proposal email queued for resend.']);
    }

    /** Duplicate a proposal as a new draft. */
    public function duplicate(Proposal $proposal)
    {
        $copy = $proposal->replicate();
        $copy->title = $proposal->title . ' (Copy)';
        $copy->status = 'draft';
        $copy->token = \Illuminate\Support\Str::random(40);
        $copy->responded_at = null;
        $copy->client_note = null;
        $copy->viewed_at = null;
        $copy->save();

        LogActivity::record('proposal_duplicated', $copy, "Duplicated proposal: {$proposal->title}");

        return response()->json([
            'message' => 'Proposal duplicated as draft.',
            'drawer_url' => route('admin.proposals.edit', $copy),
        ]);
    }
}
