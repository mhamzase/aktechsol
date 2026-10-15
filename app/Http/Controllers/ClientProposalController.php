<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Support\NotifyAdmins;
use Illuminate\Http\Request;

class ClientProposalController extends Controller
{
    /** Show the proposal to the client via secure token link. */
    public function show(string $token)
    {
        $proposal = Proposal::where('token', $token)->firstOrFail();

        // Auto-expire if past valid_until.
        if ($proposal->isExpired() && $proposal->status !== 'expired') {
            $proposal->update(['status' => 'expired']);
        }

        return view('proposals.show', compact('proposal'));
    }

    /** Client responds: accept / reject / request changes. */
    public function respond(Request $request, string $token)
    {
        $proposal = Proposal::where('token', $token)->firstOrFail();

        if (! in_array($proposal->status, ['sent', 'changes_requested'])) {
            return back()->with('error', 'This proposal is no longer open for response.');
        }

        $validated = $request->validate([
            'action'      => 'required|in:accepted,rejected,changes_requested',
            'client_note' => 'nullable|string|max:2000',
        ]);

        $proposal->update([
            'status'       => $validated['action'],
            'client_note'  => $validated['client_note'] ?? null,
            'responded_at' => now(),
        ]);

        NotifyAdmins::send(
            'proposal',
            'Proposal ' . str_replace('_', ' ', $validated['action']),
            "{$proposal->client_name} {$validated['action']} proposal: {$proposal->title}",
            route('admin.proposals.show', $proposal),
        );

        return back()->with('success', 'Thank you! Your response has been recorded.');
    }
}
