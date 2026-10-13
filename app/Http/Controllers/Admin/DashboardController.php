<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\ChatbotQuery;
use App\Models\ClientProject;
use App\Models\DemoBooking;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\JobApplication;
use App\Models\QuoteRequest;
use App\Models\Service;
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
                'revenue_month'   => Invoice::where('status', 'paid')
                    ->whereMonth('updated_at', now()->month)
                    ->whereYear('updated_at', now()->year)
                    ->sum('paid_amount'),
                'chats_unanswered'=> ChatbotQuery::where('is_answered', false)->count(),
                'services'        => Service::count(),
                'blog_posts'      => BlogPost::count(),
            ];

            // Quotes per month (last 6 months) — funnel chart.
            $quotesByMonth = QuoteRequest::select(
                    DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                    DB::raw('COUNT(*) as total')
                )
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderByRaw('MIN(created_at)')
                ->pluck('total', 'month');

            // Revenue per month (last 6 months) — from verified payments.
            $revenueByMonth = InvoicePayment::select(
                    DB::raw("DATE_FORMAT(created_at, '%b') as month"),
                    DB::raw('SUM(amount) as total')
                )
                ->where('status', 'verified')
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('month')
                ->orderByRaw('MIN(created_at)')
                ->pluck('total', 'month');

            // Project pipeline counts — doughnut chart.
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
                ['quotes_new', 'projects_active', 'revenue_month', 'chats_unanswered', 'services', 'blog_posts'], 0
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
