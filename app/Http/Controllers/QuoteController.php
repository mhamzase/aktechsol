<?php

namespace App\Http\Controllers;

use App\Models\QuoteRequest;
use App\Models\Service;
use App\Support\NotifyAdmins;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    /** Show the Get a Quote form. */
    public function create(Request $request)
    {
        $services = Service::where('status', true)->orderBy('title')->get(['id', 'title']);
        $selectedService = $request->query('service');

        return view('quote.create', compact('services', 'selectedService'));
    }

    /** Store a new quote request. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:50',
            'company'      => 'nullable|string|max:255',
            'service_id'   => 'nullable|exists:services,id',
            'subject'      => 'nullable|string|max:255',
            'budget_range' => 'nullable|string|max:100',
            'requirements' => 'required|string|min:20|max:5000',
        ]);

        $quote = QuoteRequest::create($validated);

        NotifyAdmins::send(
            'quote',
            'New quote request',
            "{$quote->name} requested a quote" . ($quote->service ? " for {$quote->service->title}" : ''),
            route('admin.quotes.show', $quote),
        );

        // Activity log (admin context only — LogActivity skips guests)
        \App\Support\LogActivity::record('quote_request_created', $quote, "New quote request from {$quote->name}");

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Thank you! Your quote request has been received. We\'ll get back to you within 24 hours.']);
        }

        return back()->with('success', 'Thank you! Your quote request has been received.');
    }
}
