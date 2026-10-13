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
                'revenue_month'   => InvoicePayment::where('status', 'verified')
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('amount'),
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

            // Revenue per month (last 6 months) — from verified payments.
            $revenueRaw = InvoicePayment::select(
                    DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                    DB::raw('SUM(amount) as total')
                )
                ->where('status', 'verified')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->pluck('total', 'month');
            $revenueByMonth = $months->mapWithKeys(fn ($m) => [$m => (float) ($revenueRaw[$m] ?? 0)]);

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
        } catch (\Throwable $e) {
            $stats = array_fill_keys(
                ['quotes_new', 'projects_active', 'revenue_month', 'chats_unanswered'], 0
            );
            $quotesByMonth = collect();
            $revenueByMonth = collect();
            $projectPipeline = collect();
            $attention = ['quotes' => collect(), 'chats' => collect(), 'invoices' => collect(), 'demos' => collect(), 'jobs' => collect()];
        }

        return view('admin.dashboard', compact(
            'stats', 'quotesByMonth', 'revenueByMonth', 'projectPipeline', 'attention'
        ));
    }
}
