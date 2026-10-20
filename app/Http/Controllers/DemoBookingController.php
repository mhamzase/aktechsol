<?php

namespace App\Http\Controllers;

use App\Mail\DemoBookingMail;
use App\Models\DemoBooking;
use App\Models\Service;
use App\Support\AdminMail;
use App\Support\NotifyAdmins;
use Illuminate\Http\Request;

/**
 * DemoBookingController — public "Book a Demo" page + submission.
 *
 * Mirrors the Quote flow: themed page, AJAX submit with inline errors,
 * an admin bell notification and a queued admin email on every booking.
 */
class DemoBookingController extends Controller
{
    /** Available demo time slots (stored as plain text). */
    public const TIME_SLOTS = [
        '10:00 AM', '12:00 PM', '02:00 PM', '04:00 PM', '06:00 PM',
    ];

    /** Show the booking form. */
    public function create(Request $request)
    {
        $services = Service::where('status', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get(['id', 'title']);

        // Pre-select a service when arriving from a service page (?service=Title).
        $selectedService = $request->query('service');
        $timeSlots = self::TIME_SLOTS;

        return view('demo.create', compact('services', 'selectedService', 'timeSlots'));
    }

    /** Store a demo booking, notify admins (bell + queued email). */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'nullable|string|max:50',
            'service'        => 'nullable|string|max:255',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_time' => 'nullable|string|max:50',
            'message'        => 'nullable|string|max:5000',
        ]);

        $booking = DemoBooking::create($validated);

        NotifyAdmins::send(
            'demo',
            'New demo booking',
            "{$booking->name} booked a demo" . ($booking->preferred_date ? ' for ' . $booking->preferred_date->format('M d, Y') : '') . ($booking->service ? " ({$booking->service})" : ''),
            route('admin.demo-bookings.show', $booking),
        );
        AdminMail::queue(new DemoBookingMail($booking));

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json(['message' => 'Demo booked successfully! We will confirm your slot by email shortly.']);
        }

        return redirect()->route('demo.create')->with('success', 'Demo booked successfully! We will confirm your slot by email shortly.');
    }
}
