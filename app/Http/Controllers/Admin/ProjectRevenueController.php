<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClientProject;
use App\Models\ProjectRevenueShare;
use App\Models\User;
use App\Settings\SiteSettings;
use App\Support\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ProjectRevenueController (admin) — revenue distribution for
 * delivered client projects.
 *
 * The split is percentage-based: one company row plus one row per
 * assigned team member, always totalling 100%. Amounts come from the
 * project's actually received payment (its invoices' paid amounts).
 * A share is marked paid only with a payment-proof screenshot; once
 * any share is paid, the percentages lock.
 */
class ProjectRevenueController extends Controller
{
    /** Drawer: set or edit the distribution for a delivered project. */
    public function edit(ClientProject $clientProject)
    {
        abort_unless($clientProject->status === 'delivered', 403, 'Revenue can only be distributed for delivered projects.');

        $clientProject->load(['team', 'revenueShares']);
        $received = $clientProject->receivedAmount();
        $locked = $clientProject->revenueLocked();
        $companyName = $this->companyName();

        // Rows for the form: existing shares, or sensible defaults
        // (company 20%, the rest split equally across the team).
        $rows = [];
        if ($clientProject->revenueShares->isNotEmpty()) {
            foreach ($clientProject->revenueShares as $share) {
                $rows[] = [
                    'user_id'     => $share->user_id,
                    'name'        => $share->member_name,
                    'is_company'  => $share->isCompany(),
                    'percentage'  => (float) $share->percentage,
                ];
            }
        } elseif ($clientProject->team->isEmpty()) {
            $rows[] = ['user_id' => null, 'name' => $companyName, 'is_company' => true, 'percentage' => 100];
        } else {
            $rows[] = ['user_id' => null, 'name' => $companyName, 'is_company' => true, 'percentage' => 20];
            $team = $clientProject->team;
            $per = round(80 / $team->count(), 2);
            foreach ($team as $i => $member) {
                $pct = $i === $team->count() - 1 ? round(100 - 20 - $per * ($team->count() - 1), 2) : $per;
                $rows[] = ['user_id' => $member->id, 'name' => $member->name, 'is_company' => false, 'percentage' => $pct];
            }
        }

        return view('admin.client-projects._revenue', [
            'project'     => $clientProject,
            'rows'        => $rows,
            'received'    => $received,
            'distributed' => (float) $clientProject->revenueShares->sum('amount'),
            'locked'      => $locked,
            'hasShares'   => $clientProject->revenueShares->isNotEmpty(),
        ]);
    }

    /** Save (or re-save, while nothing is paid yet) the distribution. */
    public function update(Request $request, ClientProject $clientProject)
    {
        if ($clientProject->status !== 'delivered') {
            return response()->json(['message' => 'Revenue can only be distributed for delivered projects.'], 422);
        }

        if ($clientProject->revenueLocked()) {
            return response()->json(['message' => 'This distribution is locked because a share has already been paid.'], 422);
        }

        $base = $clientProject->receivedAmount();
        if ($base <= 0) {
            return response()->json(['message' => 'No payment has been received for this project yet, so there is nothing to distribute.'], 422);
        }

        $validated = $request->validate([
            'shares'              => 'required|array|min:1',
            'shares.*.user_id'    => 'nullable|exists:users,id',
            'shares.*.percentage' => 'required|numeric|min:0|max:100',
        ]);

        $total = collect($validated['shares'])->sum(fn ($s) => (float) $s['percentage']);
        if (abs($total - 100) > 0.01) {
            return response()->json([
                'message' => 'Percentages must add up to exactly 100%. They currently total ' . rtrim(rtrim(number_format($total, 2), '0'), '.') . '%.',
                'errors'  => ['shares' => ['Percentages must total 100%.']],
            ], 422);
        }

        // Amounts from the received total; the largest share absorbs
        // any rounding remainder so the amounts sum exactly.
        $rows = collect($validated['shares'])->map(function ($s) use ($base) {
            return [
                'user_id'    => ! empty($s['user_id']) ? (int) $s['user_id'] : null,
                'percentage' => round((float) $s['percentage'], 2),
                'amount'     => round($base * (float) $s['percentage'] / 100, 2),
            ];
        });
        $diff = round($base - $rows->sum('amount'), 2);
        if ($diff !== 0.0) {
            $largest = $rows->sortByDesc('amount')->keys()->first();
            $rows[$largest]['amount'] = round($rows[$largest]['amount'] + $diff, 2);
        }

        $names = User::whereIn('id', $rows->pluck('user_id')->filter())->pluck('name', 'id');
        $companyName = $this->companyName();

        DB::transaction(function () use ($clientProject, $rows, $names, $companyName) {
            $clientProject->revenueShares()->delete();
            foreach ($rows as $row) {
                $clientProject->revenueShares()->create([
                    'user_id'     => $row['user_id'],
                    'member_name' => $row['user_id'] ? ($names[$row['user_id']] ?? 'Team Member') : $companyName,
                    'percentage'  => $row['percentage'],
                    'amount'      => $row['amount'],
                    'status'      => 'pending',
                ]);
            }
        });

        LogActivity::record('revenue_distributed', $clientProject, "Revenue distribution saved for {$clientProject->title} (" . \App\Support\Money::format($base, $clientProject->currency) . ')');

        return response()->json(['message' => 'Revenue distribution saved.']);
    }

    /** Drawer: mark one share as paid (payment proof required). */
    public function paidForm(ClientProject $clientProject, ProjectRevenueShare $share)
    {
        abort_unless($share->client_project_id === $clientProject->id, 404);
        abort_if($share->isPaid(), 403, 'This share is already paid.');

        return view('admin.client-projects._paid', ['project' => $clientProject, 'share' => $share]);
    }

    /** Mark a share paid and store its payment-proof screenshot. */
    public function markPaid(Request $request, ClientProject $clientProject, ProjectRevenueShare $share)
    {
        abort_unless($share->client_project_id === $clientProject->id, 404);

        if ($share->isPaid()) {
            return response()->json(['message' => 'This share is already marked as paid.'], 422);
        }

        $request->validate([
            'proof' => 'required|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $share->addMediaFromRequest('proof')->toMediaCollection('payment_proof');
        $share->update(['status' => 'paid', 'paid_at' => now()]);

        LogActivity::record('revenue_share_paid', $clientProject, "Paid {$share->member_name} " . \App\Support\Money::format($share->amount, $clientProject->currency) . " for {$clientProject->title}");

        return response()->json(['message' => "{$share->member_name}'s share marked as paid."]);
    }

    /** The company label from Settings (fallback to the default brand). */
    protected function companyName(): string
    {
        try {
            return app(SiteSettings::class)->site_name ?: 'AK Tech SOL';
        } catch (\Throwable $e) {
            return 'AK Tech SOL';
        }
    }
}
