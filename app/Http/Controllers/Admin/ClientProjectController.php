<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ProjectUpdateMail;
use App\Mail\ReviewRequestMail;
use App\Models\ClientProject;
use App\Models\Review;
use App\Models\User;
use App\Support\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientProject::with(['team', 'proposal', 'revenueShares'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('title', 'like', "%{$s}%")
                ->orWhere('client_name', 'like', "%{$s}%")
                ->orWhere('client_email', 'like', "%{$s}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = (int) $request->get('per_page', 15);
        $projects = $query->paginate($perPage)->withQueryString();

        $stats = [
            'total'       => ClientProject::count(),
            'received'    => ClientProject::where('status', 'received')->count(),
            'in_progress' => ClientProject::where('status', 'in_progress')->count(),
            'review'      => ClientProject::whereIn('status', ['review', 'revision'])->count(),
            'delivered'   => ClientProject::where('status', 'delivered')->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.client-projects._rows', ['projects' => $projects])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $projects])->render(),
                'stats'      => "Showing {$projects->firstItem()}–{$projects->lastItem()} of {$projects->total()}",
            ]);
        }

        return view('admin.client-projects.index', compact('projects', 'stats'));
    }

    public function create()
    {
        $project = new ClientProject();
        $project->currency = \App\Support\Money::code(app(\App\Settings\SiteSettings::class)->default_currency);
        $users = User::orderBy('name')->get(['id', 'name']);
        $proposalOptions = $this->proposalOptions(null);
        return view('admin.client-projects.form', compact('project', 'users', 'proposalOptions'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $teamIds = $data['team'] ?? [];
        unset($data['team']);

        $project = ClientProject::create($data);
        $project->team()->sync($teamIds);
        LogActivity::record('project_created', $project, "Created project: {$project->title}");

        return response()->json(['message' => 'Project created.', 'id' => $project->id]);
    }

    public function show(ClientProject $clientProject)
    {
        $clientProject->load(['team', 'proposal.quoteRequest.service', 'updates.author', 'revenueShares']);
        $project = $clientProject;

        if (request()->ajax() || request()->expectsJson()) {
            return view('admin.client-projects.show', compact('project'));
        }

        // Direct landing (email / notification link): listing auto-opens the drawer.
        return redirect()->route('admin.client-projects.index', ['view' => $project->id]);
    }

    public function edit(ClientProject $clientProject)
    {
        $project = $clientProject;
        $users = User::orderBy('name')->get(['id', 'name']);
        $proposalOptions = $this->proposalOptions($project);
        return view('admin.client-projects.form', compact('project', 'users', 'proposalOptions'));
    }

    public function update(Request $request, ClientProject $clientProject)
    {
        $wasDelivered = $clientProject->status === 'delivered';
        $data = $this->validateData($request, $clientProject);
        $teamIds = $data['team'] ?? [];
        unset($data['team']);

        $clientProject->update($data);
        $clientProject->team()->sync($teamIds);
        LogActivity::record('project_updated', $clientProject, "Updated project: {$clientProject->title}");

        // Auto-send a review request when the project is newly delivered.
        if (! $wasDelivered && $clientProject->status === 'delivered' && $clientProject->client_email) {
            $this->sendReviewRequest($clientProject);
        }

        return response()->json(['message' => 'Project updated.']);
    }

    /** Queue a review request email (creates the token placeholder if needed). */
    protected function sendReviewRequest(ClientProject $clientProject): void
    {
        try {
            // Reuse an existing pending placeholder if one exists.
            $review = Review::firstOrCreate(
                ['client_project_id' => $clientProject->id, 'reviewer_type' => 'client', 'status' => 'pending'],
                ['reviewer_name' => $clientProject->client_name, 'rating' => 5, 'token' => Str::random(48)]
            );
            if (! $review->token) {
                $review->update(['token' => Str::random(48)]);
            }

            Mail::to($clientProject->client_email)->queue(new ReviewRequestMail($clientProject, $review->token));
            LogActivity::record('review_requested', $review, "Auto-sent review request for {$clientProject->title}");
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function destroy(ClientProject $clientProject)
    {
        $clientProject->delete();
        LogActivity::record('project_deleted', null, "Deleted project: {$clientProject->title}");

        return response()->json(['message' => 'Project deleted.']);
    }

    /** Add a timeline update. */
    public function addUpdate(Request $request, ClientProject $clientProject)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'note'              => 'nullable|string|max:2000',
            'is_client_visible' => 'boolean',
        ]);

        $update = $clientProject->updates()->create([
            'title'             => $validated['title'],
            'note'              => $validated['note'] ?? null,
            'is_client_visible' => $request->boolean('is_client_visible'),
            'created_by'        => auth()->id(),
        ]);

        // Email the client about client-visible updates (queued). Internal
        // updates never leave the admin panel.
        if ($update->is_client_visible && $clientProject->client_email) {
            Mail::to($clientProject->client_email)
                ->queue(new ProjectUpdateMail($clientProject, $update));
        }

        return response()->json(['message' => 'Update added.']);
    }

    /** Delete a timeline update. */
    public function deleteUpdate(ClientProject $clientProject, $updateId)
    {
        $clientProject->updates()->whereKey($updateId)->delete();

        return response()->json(['message' => 'Update deleted.']);
    }

    protected function validateData(Request $request, ?ClientProject $project = null): array
    {
        $data = $request->validate([
            'proposal_id'  => 'required|exists:proposals,id',
            'title'        => 'required|string|max:255',
            'client_name'  => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'currency'     => 'nullable|in:' . implode(',', \App\Support\Money::codes()),
            'team'         => 'nullable|array',
            'team.*'       => 'exists:users,id',
            'status'       => 'required|in:received,in_progress,review,revision,delivered',
            'progress'     => 'required|integer|min:0|max:100',
            'started_at'   => 'nullable|date',
            'deadline'     => 'nullable|date|after_or_equal:started_at',
        ]);

        $this->guardProposalLink((int) $data['proposal_id'], $project);

        $data['currency'] = \App\Support\Money::code($data['currency'] ?? null);

        return $data;
    }

    /**
     * Select options for the proposal picker: ACCEPTED proposals that
     * are not linked to any project yet, plus (on edit) the project's
     * own current proposal. Labels carry title, client and amount so
     * the right deal is easy to find in the search list.
     */
    protected function proposalOptions(?ClientProject $project): array
    {
        $proposals = \App\Models\Proposal::where('status', 'accepted')
            ->where(function ($q) use ($project) {
                $q->whereDoesntHave('project');
                if ($project?->proposal_id) {
                    $q->orWhere('id', $project->proposal_id);
                }
            })
            ->latest()
            ->get();

        return $proposals->mapWithKeys(fn ($p) => [
            $p->id => $p->title . ' — ' . $p->client_name . ' (' . \App\Support\Money::format($p->amount, $p->currency) . ')',
        ])->all();
    }

    /**
     * One-to-one guard: a project can only link to an ACCEPTED
     * proposal, and that proposal cannot belong to another project.
     */
    protected function guardProposalLink(int $proposalId, ?ClientProject $project): void
    {
        $proposal = \App\Models\Proposal::find($proposalId);

        if (! $proposal || $proposal->status !== 'accepted') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'proposal_id' => 'Only an accepted proposal can be linked to a project.',
            ]);
        }

        $taken = ClientProject::where('proposal_id', $proposalId)
            ->when($project, fn ($q) => $q->whereKeyNot($project->id))
            ->exists();

        if ($taken) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'proposal_id' => 'This proposal is already linked to another project.',
            ]);
        }
    }
}
