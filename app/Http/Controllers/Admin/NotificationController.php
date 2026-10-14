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
    public function index(Request $request)
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'html' => view('admin.notifications._rows', compact('notifications'))->render(),
                'next_page' => $notifications->hasMorePages() ? $notifications->currentPage() + 1 : null,
            ]);
        }

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

    /** Delete a single notification. */
    public function destroy(Request $request, string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $wasUnread = is_null($notification->read_at);
        $notification->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Notification deleted.', 'was_unread' => $wasUnread]);
        }

        return back()->with('success', 'Notification deleted.');
    }
}
