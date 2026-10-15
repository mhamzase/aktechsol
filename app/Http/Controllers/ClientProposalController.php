<?php

namespace App\Http\Controllers;

use App\Mail\ProposalResponseMail;
use App\Models\Proposal;
use App\Settings\SiteSettings;
use App\Support\NotifyAdmins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    /** Client responds: accept / reject / request changes (AJAX). */
    public function respond(Request $request, string $token)
    {
        $proposal = Proposal::where('token', $token)->firstOrFail();

        if (! in_array($proposal->status, ['sent', 'changes_requested'])) {
            $msg = 'This proposal is no longer open for response.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $validated = $request->validate([
            'action'      => 'required|in:accepted,rejected,changes_requested',
            'client_note' => 'required|string|min:10|max:2000',
        ]);

        $proposal->update([
            'status'       => $validated['action'],
            'client_note'  => $validated['client_note'] ?? null,
            'responded_at' => now(),
        ]);

        // Notify admins in-app.
        NotifyAdmins::send(
            'proposal',
            'Proposal ' . str_replace('_', ' ', $validated['action']),
            "{$proposal->client_name} {$validated['action']} proposal: {$proposal->title}",
            route('admin.proposals.show', $proposal),
        );

        // Email the admin (settings notification email, queued).
        try {
            $adminEmail = app(SiteSettings::class)->notification_email;
            if ($adminEmail) {
                Mail::to($adminEmail)->queue(new ProposalResponseMail($proposal, $validated['action']));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        $messages = [
            'accepted' => 'Thank you! You have accepted the proposal. Our team will be in touch shortly.',
            'changes_requested' => 'Thank you! We have received your feedback and will update the proposal.',
            'rejected' => 'Your response has been recorded. Thank you for your time.',
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => $messages[$validated['action']], 'status' => $proposal->status]);
        }

        return back()->with('success', $messages[$validated['action']]);
    }
}
