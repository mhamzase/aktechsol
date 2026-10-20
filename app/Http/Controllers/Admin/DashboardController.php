<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotQuery;
use App\Models\ClientProject;
use App\Models\DemoBooking;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\JobApplication;
use App\Models\QuoteRequest;
use Illuminate\Support\Facades\DB;

/**
 * DashboardController — admin home: stat cards, charts and the
 * "Needs Attention" widget (pending quotes, unanswered chats,
 * overdue invoices, new applications).
 */
class DashboardController extends Controller
{
    public function index()
    {
        // Wrap in try/catch so the dashboard survives partially-migrated DBs.
        try {
            $stats = [
                'quotes_new'      => QuoteRequest::where('status', 'new')->count(),
                'projects_active' => ClientProject::whereIn('status', ['received', 'in_progress', 'review', 'revision'])->count(),
                'revenue_month'   => $this->revenueByCurrency(
                    InvoicePayment::where('invoice_payments.status', 'verified')
                        ->whereMonth('invoice_payments.created_at', now()->month)
                        ->whereYear('invoice_payments.created_at', now()->year)
                ),
                'chats_unanswered'=> ChatbotQuery::where('is_answered', false)->count(),
            ];

            // Last 6 months skeleton so empty months still render as 0.
            $months = collect();
            for ($i = 5; $i >= 0; $i--) {
                $months->push(now()->subMonths($i)->format('M'));
            }

            // Quotes per month (last 6 months).
            $quotesRaw = QuoteRequest::select(
                    DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->pluck('total', 'month');
            $quotesByMonth = $months->mapWithKeys(fn ($m) => [$m => (int) ($quotesRaw[$m] ?? 0)]);

            // Revenue per month (last 6 months) — from verified payments,
            // kept SEPARATE per currency (amounts are never mixed).
            $revenueRaw = InvoicePayment::query()
                ->join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id')
                ->select(
                    DB::raw("DATE_FORMAT(invoice_payments.created_at, '%b') as month"),
                    'invoices.currency',
                    DB::raw('SUM(invoice_payments.amount) as total')
                )
                ->where('invoice_payments.status', 'verified')
                ->where('invoice_payments.created_at', '>=', now()->subMonths(6))
                ->groupBy('month', 'invoices.currency')
                ->get();
            $revenueByMonth = collect();
            foreach (\App\Support\Money::CURRENCIES as $cur) {
                $byMonth = $revenueRaw->where('currency', $cur)->pluck('total', 'month');
                $revenueByMonth[$cur] = $months->mapWithKeys(fn ($m) => [$m => (float) ($byMonth[$m] ?? 0)]);
            }

            // Project pipeline counts — pie chart.
            $projectPipeline = ClientProject::select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status');

            // Needs Attention widget.
            $attention = [
                'quotes'   => QuoteRequest::where('status', 'new')->latest()->take(5)->get(),
                'chats'    => ChatbotQuery::where('is_answered', false)->latest()->take(5)->get(),
                'invoices' => Invoice::whereIn('status', ['unpaid', 'partially_paid', 'overdue'])->latest()->take(5)->get(),
                'demos'    => DemoBooking::where('status', 'new')->latest()->take(5)->get(),
                'jobs'     => JobApplication::where('status', 'new')->latest()->take(5)->get(),
            ];

            // Trends for the stat cards.
            $revenueLastMonth = $this->revenueByCurrency(
                InvoicePayment::where('invoice_payments.status', 'verified')
                    ->whereMonth('invoice_payments.created_at', now()->subMonth()->month)
                    ->whereYear('invoice_payments.created_at', now()->subMonth()->year)
            );
            $trends = [
                'quotes_week'        => QuoteRequest::where('created_at', '>=', now()->startOfWeek())->count(),
                'revenue_last_month' => $revenueLastMonth,
            ];
        } catch (\Throwable $e) {
            $stats = array_fill_keys(
                ['quotes_new', 'projects_active', 'chats_unanswered'], 0
            );
            $stats['revenue_month'] = ['PKR' => 0.0, 'USD' => 0.0];
            $quotesByMonth = collect();
            $revenueByMonth = collect(['PKR' => collect(), 'USD' => collect()]);
            $projectPipeline = collect();
            $attention = ['quotes' => collect(), 'chats' => collect(), 'invoices' => collect(), 'demos' => collect(), 'jobs' => collect()];
            $trends = ['quotes_week' => 0, 'revenue_last_month' => ['PKR' => 0.0, 'USD' => 0.0]];
        }

        return view('admin.dashboard', compact(
            'stats', 'quotesByMonth', 'revenueByMonth', 'projectPipeline', 'attention', 'trends'
        ));
    }

    /**
     * Sum a verified-payments query per currency (via the invoice's
     * currency). Always returns both keys: ['PKR' => x, 'USD' => y].
     */
    protected function revenueByCurrency($query): array
    {
        $rows = $query
            ->join('invoices', 'invoices.id', '=', 'invoice_payments.invoice_id')
            ->select('invoices.currency', DB::raw('SUM(invoice_payments.amount) as total'))
            ->groupBy('invoices.currency')
            ->pluck('total', 'currency');

        return [
            'PKR' => (float) ($rows['PKR'] ?? 0),
            'USD' => (float) ($rows['USD'] ?? 0),
        ];
    }
}
