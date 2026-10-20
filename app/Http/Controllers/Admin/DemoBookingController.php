<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemoBooking;
use App\Support\LogActivity;
use Illuminate\Http\Request;

/**
 * DemoBookingController (admin) — "Book a Demo" requests from the website.
 * Listing + detail drawer + status management, mirroring Quote Requests.
 */
class DemoBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = DemoBooking::latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('service', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate($request->get('per_page', 10));
        $stats = $this->stats();

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.demo-bookings._rows', ['bookings' => $bookings])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $bookings])->render(),
                'stats'      => "Showing {$bookings->firstItem()}–{$bookings->lastItem()} of {$bookings->total()}",
            ]);
        }

        return view('admin.demo-bookings.index', compact('bookings', 'stats'));
    }

    public function show(DemoBooking $demoBooking)
    {
        if (request()->ajax() || request()->expectsJson()) {
            return view('admin.demo-bookings.show', ['booking' => $demoBooking]);
        }

        return redirect()->route('admin.demo-bookings.index', ['view' => $demoBooking->id]);
    }

    public function updateStatus(Request $request, DemoBooking $demoBooking)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', DemoBooking::STATUSES),
        ]);

        $demoBooking->update(['status' => $validated['status']]);
        LogActivity::record('demo_status_updated', $demoBooking, "Demo booking #{$demoBooking->id} status → {$validated['status']}");

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Demo booking status updated.', 'status' => $demoBooking->status, 'stats' => $this->stats()]);
        }

        return back()->with('success', 'Demo booking status updated.');
    }

    public function destroy(DemoBooking $demoBooking)
    {
        $demoBooking->delete();
        LogActivity::record('demo_deleted', null, "Deleted demo booking from {$demoBooking->name}");

        if (request()->ajax()) {
            return response()->json(['message' => 'Demo booking deleted.']);
        }

        return redirect()->route('admin.demo-bookings.index')->with('success', 'Demo booking deleted.');
    }

    /** Counters for the listing stat cards. */
    protected function stats(): array
    {
        return [
            'new'       => DemoBooking::where('status', 'new')->count(),
            'contacted' => DemoBooking::where('status', 'contacted')->count(),
            'scheduled' => DemoBooking::where('status', 'scheduled')->count(),
            'done'      => DemoBooking::where('status', 'done')->count(),
            'total'     => DemoBooking::count(),
        ];
    }
}
