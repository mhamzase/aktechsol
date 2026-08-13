<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

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

        $subscribers = $query->paginate(10)->withQueryString();

        return view('admin.newsletter-subscribers.index', compact('subscribers'));
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber)
    {
        $newsletterSubscriber->delete();

        return redirect()->route('admin.newsletter-subscribers.index')
                         ->with('success', 'Subscriber deleted successfully.');
    }
}
