<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\QuoteRequest;
use App\Support\LogActivity;
use App\Support\NotifyAdmins;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
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
        return view('admin.proposals.form', ['proposal' => new Proposal(), 'quotes' => $quotes]);
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
            'valid_until'      => 'nullable|date|after:today',
            'milestones'       => 'nullable|array',
            'milestones.*.title'  => 'required_with:milestones|string|max:255',
            'milestones.*.amount' => 'required_with:milestones|numeric|min:0',
        ]);

        $proposal = Proposal::create($validated);
        LogActivity::record('proposal_created', $proposal, "Created proposal: {$proposal->title}");

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
            'valid_until'      => 'nullable|date|after:today',
            'milestones'       => 'nullable|array',
            'milestones.*.title'  => 'required_with:milestones|string|max:255',
            'milestones.*.amount' => 'required_with:milestones|numeric|min:0',
        ]);

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
}
