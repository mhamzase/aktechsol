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
