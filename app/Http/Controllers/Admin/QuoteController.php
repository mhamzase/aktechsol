<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Support\LogActivity;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = QuoteRequest::with('service')->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('requirements', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $quotes = $query->paginate($request->get('per_page', 10));

        $stats = [
            'new'      => QuoteRequest::where('status', 'new')->count(),
            'reviewed' => QuoteRequest::where('status', 'reviewed')->count(),
            'quoted'   => QuoteRequest::where('status', 'quoted')->count(),
            'total'    => QuoteRequest::count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.quotes._rows', ['quotes' => $quotes])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $quotes])->render(),
                'stats'      => "Showing {$quotes->firstItem()}–{$quotes->lastItem()} of {$quotes->total()}",
            ]);
        }

        return view('admin.quotes.index', compact('quotes', 'stats'));
    }

    public function show(QuoteRequest $quote)
    {
        $quote->load('service');

        if (request()->ajax() || request()->expectsJson()) {
            return view('admin.quotes.show', compact('quote'));
        }

        return redirect()->route('admin.quotes.index', ['view' => $quote->id]);
    }

    public function updateStatus(Request $request, QuoteRequest $quote)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', QuoteRequest::STATUSES),
        ]);

        $quote->update(['status' => $validated['status']]);
        LogActivity::record('quote_status_updated', $quote, "Quote #{$quote->id} status → {$validated['status']}");

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Quote status updated.', 'status' => $quote->status]);
        }

        return back()->with('success', 'Quote status updated.');
    }

    public function destroy(QuoteRequest $quote)
    {
        $quote->delete();
        LogActivity::record('quote_deleted', null, "Deleted quote request from {$quote->name}");

        if (request()->ajax()) {
            return response()->json(['message' => 'Quote request deleted.']);
        }

        return redirect()->route('admin.quotes.index')->with('success', 'Quote request deleted.');
    }
}
