<?php

namespace App\Http\Controllers;

use App\Models\ClientProject;

class ClientProjectTrackingController extends Controller
{
    /** Public project tracking page via secure token. */
    public function show(string $token)
    {
        $project = ClientProject::where('token', $token)
            ->with(['visibleUpdates.author', 'assignee', 'invoices' => fn($q) => $q->latest()])
            ->firstOrFail();

        // Backfill tokens for invoices created before the token column existed.
        foreach ($project->invoices as $inv) {
            if (! $inv->token) {
                $inv->update(['token' => \Illuminate\Support\Str::random(48)]);
            }
        }

        return view('projects.track', compact('project'));
    }
}
