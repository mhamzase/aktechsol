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

        return view('projects.track', compact('project'));
    }
}
