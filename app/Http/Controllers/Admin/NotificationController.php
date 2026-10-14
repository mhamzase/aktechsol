<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * NotificationController — admin notification center.
 * Database notifications created by platform events (new quote,
 * unanswered chat, payment proof, ...) are listed here.
 */
class NotificationController extends Controller
{
    /** Paginated notification feed. */
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    /** Mark every notification as read. */
    public function readAll(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'All notifications marked as read.']);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /** Mark a single notification as read. */
    public function read(Request $request, string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Marked as read.']);
        }

        return back();
    }

    /** Unread count for bell polling. */
    public function unreadCount()
    {
        return response()->json([
            'count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
