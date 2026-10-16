<?php

namespace App\Http\Controllers;

use App\Mail\ProposalResponseMail;
use App\Models\Proposal;
use App\Settings\SiteSettings;
use App\Support\NotifyAdmins;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientProposalController extends Controller
{
    /** Show the proposal to the client via secure token link. */
    public function show(string $token)
    {
        $proposal = Proposal::where('token', $token)->firstOrFail();

        // Track first view.
        if (! $proposal->viewed_at) {
            $proposal->update(['viewed_at' => now()]);
        }

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

        // Auto-create a client project when the proposal is accepted.
        if ($validated['action'] === 'accepted' && ! $proposal->project) {
            $project = \App\Models\ClientProject::create([
                'proposal_id'  => $proposal->id,
                'title'        => $proposal->title,
                'client_name'  => $proposal->client_name,
                'client_email' => $proposal->client_email,
                'status'       => 'received',
                'started_at'   => today(),
            ]);

            // Seed the first timeline entry.
            $project->updates()->create([
                'title'            => 'Project received',
                'note'             => 'Your proposal was accepted. Our team will begin work shortly.',
                'is_client_visible' => true,
            ]);

            NotifyAdmins::send(
                'project',
                'New project created',
                "Project '{$project->title}' created from accepted proposal.",
                route('admin.projects.show', $project),
            );
        }

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

    /** Client downloads proposal as PDF. */
    public function pdf(string $token)
    {
        $proposal = Proposal::where('token', $token)->firstOrFail();
        $pdf = Pdf::loadView('proposals.pdf', compact('proposal'));
        return $pdf->download(Str::slug($proposal->title) . '.pdf');
    }
}
