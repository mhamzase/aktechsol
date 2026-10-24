<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinanceEntry;
use App\Models\ProjectRevenueShare;
use App\Support\LogActivity;
use App\Support\Money;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * FinanceController (admin) — the company ledger: Revenue, Expenses
 * and Withdrawals in one tabbed page.
 *
 * Revenue has two sources: MANUAL entries typed in here, and PROJECT
 * entries created automatically when a project's company revenue
 * share is marked paid (see FinanceEntry::recordProjectRevenue).
 * Project entries are read-only here; they are managed from the
 * project itself.
 *
 * Money rules (Hamza's decisions, 2026-10-24):
 *  - Net balance = revenue − expenses − withdrawals, per currency.
 *  - A withdrawal can never exceed the net balance of its currency.
 *  - Overall PKR figures come from each entry's own rate snapshot
 *    (Option B), never from today's rate applied to old entries.
 */
class FinanceController extends Controller
{
    public const TYPES = ['revenue', 'expense', 'withdrawal'];

    /** Page shell: three tabs, each filled by its own AJAX calls. */
    public function index()
    {
        return view('admin.finance.index');
    }

    /** AJAX rows for one tab's table. */
    public function listing(Request $request, string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        $query = FinanceEntry::where('type', $type)
            ->with('revenueShare.project')
            ->orderByDesc('entry_date')->orderByDesc('id');

        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('note', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $page = $query->paginate($perPage > 0 ? $perPage : 10);

        return response()->json([
            'rows' => view('admin.finance._rows', [
                'entries' => $page->items(),
                'type' => $type,
            ])->render(),
            'pagination' => view('admin.partials.ajax-pagination', ['items' => $page])->render(),
            'stats' => "Showing {$page->firstItem()}–{$page->lastItem()} of {$page->total()}",
        ]);
    }

    /**
     * Stats cards + chart series for one tab, honouring the date
     * range (from/to on entry_date). Card texts are formatted here so
     * the client only has to drop them into the card shells.
     */
    public function summary(Request $request)
    {
        $type = (string) $request->input('type', 'revenue');
        abort_unless(in_array($type, self::TYPES, true), 404);

        $from = $request->filled('from') ? Carbon::parse($request->input('from'))->startOfDay() : null;
        $to = $request->filled('to') ? Carbon::parse($request->input('to'))->endOfDay() : null;

        $inRange = function ($query) use ($from, $to) {
            if ($from) {
                $query->whereDate('entry_date', '>=', $from->toDateString());
            }
            if ($to) {
                $query->whereDate('entry_date', '<=', $to->toDateString());
            }
            return $query;
        };

        // Per-currency totals + converted (snapshot) PKR total in range.
        $totalsOf = function (string $t, ?string $source = null) use ($inRange) {
            $query = $inRange(FinanceEntry::where('type', $t));
            if ($source) {
                $query->where('source', $source);
            }
            $perCurrency = (clone $query)->selectRaw('currency, SUM(amount) as total')
                ->groupBy('currency')->pluck('total', 'currency')
                ->map(fn ($v) => (float) $v)->all();

            return [
                'per_currency' => $perCurrency,
                'pkr' => (float) (clone $query)->sum('amount_pkr'),
                'missing' => (clone $query)->whereNull('amount_pkr')
                    ->where('currency', '!=', 'PKR')
                    ->distinct()->pluck('currency')->all(),
            ];
        };

        $totals = $totalsOf($type);
        $cards = [];

        // Card 1: this tab's total in range (overall PKR + per-currency).
        $labels = ['revenue' => 'Total Revenue', 'expense' => 'Total Expenses', 'withdrawal' => 'Total Withdrawals'];
        $cards['total'] = [
            'label' => $labels[$type],
            'main' => Money::format($totals['pkr'], 'PKR'),
            'sub' => $this->partsLine($totals['per_currency']) ?: 'Nothing in this range yet',
        ];

        if ($type === 'revenue') {
            $project = $totalsOf('revenue', 'project');
            $manual = $totalsOf('revenue', 'manual');
            $cards['a'] = ['label' => 'From Projects', 'main' => Money::format($project['pkr'], 'PKR'), 'sub' => $this->partsLine($project['per_currency']) ?: 'No paid company shares in range'];
            $cards['b'] = ['label' => 'Manual Revenue', 'main' => Money::format($manual['pkr'], 'PKR'), 'sub' => $this->partsLine($manual['per_currency']) ?: 'No manual entries in range'];
            // Pending = company shares set but not yet paid (current state).
            // Columns MUST stay qualified: client_projects also has a
            // `status` column, so a bare where('status') is ambiguous
            // in the join and MySQL rejects the whole query (500).
            $pending = ProjectRevenueShare::whereNull('project_revenue_shares.user_id')
                ->where('project_revenue_shares.status', 'pending')
                ->join('client_projects', 'client_projects.id', '=', 'project_revenue_shares.client_project_id')
                ->selectRaw('client_projects.currency as currency, SUM(project_revenue_shares.amount) as total')
                ->groupBy('client_projects.currency')->pluck('total', 'currency')
                ->map(fn ($v) => (float) $v)->all();
            $cards['c'] = ['label' => 'Pending From Projects', 'main' => $this->partsLine($pending) ?: 'Rs 0', 'sub' => 'Company shares set, not yet paid'];
        } elseif ($type === 'expense') {
            $count = $inRange(FinanceEntry::where('type', 'expense'))->count();
            $top = $inRange(FinanceEntry::where('type', 'expense'))->whereNotNull('category')
                ->selectRaw('category, SUM(amount_pkr) as pkr_total')
                ->groupBy('category')->orderByDesc('pkr_total')->first();
            $cards['a'] = ['label' => 'Entries', 'main' => (string) $count, 'sub' => 'Expenses in this range'];
            $cards['b'] = ['label' => 'Top Category', 'main' => $top->category ?? 'None', 'sub' => $top ? Money::format((float) $top->pkr_total, 'PKR') . ' in this range' : 'No categorised expenses yet'];
            $cards['c'] = ['label' => 'Net Balance (Overall PKR)', 'main' => Money::format(FinanceEntry::netBalancePkr(), 'PKR'), 'sub' => 'All time: revenue − expenses − withdrawals'];
        } else {
            // Withdrawal tab: show what can still be taken out.
            $balances = [];
            foreach (Money::codes() as $code) {
                $bal = FinanceEntry::netBalance($code);
                if (abs($bal) > 0.001) {
                    $balances[$code] = $bal;
                }
            }
            $cards['a'] = ['label' => 'Available Balance', 'main' => Money::format(FinanceEntry::netBalancePkr(), 'PKR'), 'sub' => $this->partsLine($balances) ?: 'Nothing available'];
            $cards['b'] = ['label' => 'Total Revenue (All Time)', 'main' => Money::format((float) FinanceEntry::where('type', 'revenue')->sum('amount_pkr'), 'PKR'), 'sub' => 'Projects + manual, converted by snapshot rates'];
            $cards['c'] = ['label' => 'Total Expenses (All Time)', 'main' => Money::format((float) FinanceEntry::where('type', 'expense')->sum('amount_pkr'), 'PKR'), 'sub' => 'Converted by snapshot rates'];
        }

        // Chart: bucket the range (days when short, months when long).
        // "All time" passes no range: span the chart across the actual
        // data instead of falling back to just the current month.
        if (! $from && ! $to) {
            $min = FinanceEntry::where('type', $type)->min('entry_date');
            $max = FinanceEntry::where('type', $type)->max('entry_date');
            if ($min && $max) {
                $from = Carbon::parse($min)->startOfDay();
                $to = Carbon::parse($max)->endOfDay();
            }
        }
        [$labels, $bucketOf] = $this->buckets($from, $to);
        $rows = $inRange(FinanceEntry::where('type', $type))
            ->select('entry_date', 'currency', 'amount')->get();
        $series = [];
        foreach ($rows->groupBy('currency') as $currency => $items) {
            $data = array_fill(0, count($labels), 0.0);
            foreach ($items as $item) {
                $idx = $bucketOf(Carbon::parse($item->entry_date));
                if ($idx !== null) {
                    $data[$idx] += (float) $item->amount;
                }
            }
            $series[] = [
                'code' => $currency,
                'name' => ucfirst($type) . ' (' . $currency . ')',
                'data' => $data,
                'symbol' => Money::symbol($currency),
                'decimals' => Money::decimals($currency),
            ];
        }
        // PKR first so it owns the visible axis, like the dashboard.
        usort($series, fn ($a, $b) => ($a['code'] === 'PKR' ? 0 : 1) <=> ($b['code'] === 'PKR' ? 0 : 1));

        return response()->json([
            'cards' => $cards,
            'missing_rates' => array_values(array_unique($totals['missing'] ?? [])),
            'labels' => $labels,
            'series' => $series,
        ]);
    }

    /** Drawer form: add a manual entry. */
    public function create(string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        return view('admin.finance.form', ['type' => $type, 'entry' => null]);
    }

    /** Save a manual entry (with the day's PKR rate snapshot). */
    public function store(Request $request, string $type)
    {
        abort_unless(in_array($type, self::TYPES, true), 404);

        $data = $this->validated($request, $type);

        if ($type === 'withdrawal') {
            $available = FinanceEntry::netBalance($data['currency']);
            if ((float) $data['amount'] > $available + 0.001) {
                return $this->balanceError($data['currency'], $available);
            }
        }

        $entry = new FinanceEntry(array_merge($data, ['type' => $type, 'source' => 'manual']));
        $entry->applySnapshot();
        $entry->save();

        if ($request->hasFile('attachment')) {
            $entry->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

        LogActivity::record('finance_' . $type . '_added', $entry, 'Added ' . $type . ' ' . Money::format($entry->amount, $entry->currency) . ': ' . $entry->title);

        return response()->json(['message' => ucfirst($type) . ' entry added.']);
    }

    /** Drawer form: edit a manual entry (project entries are locked). */
    public function edit(string $type, FinanceEntry $entry)
    {
        abort_unless(in_array($type, self::TYPES, true) && $entry->type === $type, 404);
        abort_if($entry->isProject(), 403, 'Project revenue is managed from the project itself.');

        return view('admin.finance.form', ['type' => $type, 'entry' => $entry]);
    }

    /** Update a manual entry; the PKR snapshot is re-taken at today's rate. */
    public function update(Request $request, string $type, FinanceEntry $entry)
    {
        abort_unless(in_array($type, self::TYPES, true) && $entry->type === $type, 404);
        if ($entry->isProject()) {
            return response()->json(['message' => 'Project revenue is managed from the project itself.'], 422);
        }

        $data = $this->validated($request, $type);

        if ($type === 'withdrawal') {
            // The entry's own amount is still in hand while editing.
            $available = FinanceEntry::netBalance($data['currency'])
                + ($entry->currency === $data['currency'] ? (float) $entry->amount : 0);
            if ((float) $data['amount'] > $available + 0.001) {
                return $this->balanceError($data['currency'], $available);
            }
        }

        $entry->fill($data);
        $entry->applySnapshot();
        $entry->save();

        if ($request->hasFile('attachment')) {
            $entry->clearMediaCollection('attachment');
            $entry->addMediaFromRequest('attachment')->toMediaCollection('attachment');
        }

        LogActivity::record('finance_' . $type . '_updated', $entry, 'Updated ' . $type . ' ' . Money::format($entry->amount, $entry->currency) . ': ' . $entry->title);

        return response()->json(['message' => ucfirst($type) . ' entry updated.']);
    }

    /** Delete a manual entry (project entries are locked). */
    public function destroy(string $type, FinanceEntry $entry)
    {
        abort_unless(in_array($type, self::TYPES, true) && $entry->type === $type, 404);
        if ($entry->isProject()) {
            return response()->json(['message' => 'Project revenue cannot be deleted here. It belongs to the project.'], 422);
        }

        LogActivity::record('finance_' . $type . '_deleted', $entry, 'Deleted ' . $type . ' ' . Money::format($entry->amount, $entry->currency) . ': ' . $entry->title);
        $entry->delete();

        return response()->json(['message' => ucfirst($type) . ' entry deleted.']);
    }

    /** Shared validation for store/update. */
    protected function validated(Request $request, string $type): array
    {
        $categories = $type === 'expense'
            ? FinanceEntry::EXPENSE_CATEGORIES
            : ($type === 'revenue' ? FinanceEntry::REVENUE_CATEGORIES : []);

        $data = $request->validate([
            'title' => ($type === 'withdrawal' ? 'nullable' : 'required') . '|string|max:255',
            'category' => $categories
                ? ($type === 'expense' ? 'required' : 'nullable') . '|in:' . implode(',', $categories)
                : 'nullable',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|in:' . implode(',', Money::codes()),
            'entry_date' => 'required|date',
            'note' => 'nullable|string|max:2000',
            'attachment' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $data['title'] = $data['title'] ?? 'Withdrawal';
        $data['category'] = $categories ? ($data['category'] ?? null) : null;
        unset($data['attachment']);

        return $data;
    }

    /** The withdrawal guard error, with the available amount spelled out. */
    protected function balanceError(string $currency, float $available)
    {
        $message = 'This is more than the available balance of ' . Money::format(max($available, 0), $currency) . ' in ' . $currency . '.';

        return response()->json(['message' => $message, 'errors' => ['amount' => [$message]]], 422);
    }

    /** "PKR 1,000 · $200" line for card sub-texts (non-zero currencies). */
    protected function partsLine(array $perCurrency): string
    {
        $parts = [];
        foreach (Money::codes() as $code) {
            $value = (float) ($perCurrency[$code] ?? 0);
            if (abs($value) > 0.001) {
                $parts[] = Money::format($value, $code);
            }
        }

        return implode(' · ', $parts);
    }

    /**
     * Chart buckets for a range: daily when the span is short
     * (a month or so), monthly otherwise. Returns [labels, resolver]
     * where the resolver maps a date to its bucket index (or null).
     */
    protected function buckets(?Carbon $from, ?Carbon $to): array
    {
        $start = $from ? $from->copy()->startOfDay() : now()->startOfMonth();
        $end = $to ? $to->copy()->startOfDay() : now()->startOfDay();
        if ($end->lt($start)) {
            [$start, $end] = [$end, $start];
        }

        if ($start->diffInDays($end) <= 62) {
            $labels = [];
            $map = [];
            $cursor = $start->copy();
            while ($cursor->lte($end)) {
                $map[$cursor->toDateString()] = count($labels);
                $labels[] = $cursor->format('d M');
                $cursor->addDay();
            }

            return [$labels, fn (Carbon $date) => $map[$date->toDateString()] ?? null];
        }

        $labels = [];
        $map = [];
        $cursor = $start->copy()->startOfMonth();
        while ($cursor->lte($end)) {
            $map[$cursor->format('Y-m')] = count($labels);
            $labels[] = $cursor->format('M Y');
            $cursor->addMonth();
        }

        return [$labels, fn (Carbon $date) => $map[$date->format('Y-m')] ?? null];
    }
}
