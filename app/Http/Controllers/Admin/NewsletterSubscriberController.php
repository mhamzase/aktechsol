<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterMail;
use App\Models\NewsletterSubscriber;
use App\Support\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterSubscriberController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::query()->latest();

        if ($search = $request->query('search')) {
            $query->where('email', 'like', "%{$search}%");
        }

        if ($status = $request->query('status')) {
            if ($status === 'subscribed') {
                $query->where('is_subscribed', true);
            } elseif ($status === 'unsubscribed') {
                $query->where('is_subscribed', false);
            }
        }

        $subscribers = $query->paginate($request->get('per_page', 10));

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.newsletter-subscribers._rows', ['subscribers' => $subscribers])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $subscribers])->render(),
                'stats'      => "Showing {$subscribers->firstItem()}–{$subscribers->lastItem()} of {$subscribers->total()}",
            ]);
        }

        return view('admin.newsletter-subscribers.index', compact('subscribers'));
    }

    /** Drawer form: compose a bulk email to all subscribed users. */
    public function compose()
    {
        $count = NewsletterSubscriber::where('is_subscribed', true)->count();

        return view('admin.newsletter-subscribers._compose', compact('count'));
    }

    /**
     * Queue the composed email to every SUBSCRIBED user (one queued
     * mailable each, sent in the background by the queue worker).
     */
    public function sendBulk(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string|max:50000',
        ]);

        // An "empty" rich-text body (only tags) is not a real email.
        if (trim(strip_tags($validated['body'])) === '') {
            return response()->json([
                'message' => 'Please write something in the email body.',
                'errors'  => ['body' => ['The email body is required.']],
            ], 422);
        }

        $count = 0;
        NewsletterSubscriber::where('is_subscribed', true)
            ->chunkById(200, function ($subscribers) use ($validated, &$count) {
                foreach ($subscribers as $subscriber) {
                    Mail::to($subscriber->email)->queue(new NewsletterMail($validated['subject'], $validated['body']));
                    $count++;
                }
            });

        LogActivity::record('newsletter_sent', null, "Newsletter '{$validated['subject']}' queued for {$count} subscribers");

        return response()->json(['message' => "Newsletter queued for {$count} subscriber(s). It will be sent in the background."]);
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber)
    {
        $newsletterSubscriber->delete();

        if (request()->ajax()) {
            return response()->json(['message' => 'Subscriber deleted successfully.']);
        }


        return redirect()->route('admin.newsletter-subscribers.index')
                         ->with('success', 'Subscriber deleted successfully.');
    }
}
