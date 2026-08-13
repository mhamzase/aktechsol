<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsletterSubscriptionRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;

class NewsletterController extends Controller
{
    public function subscribe(NewsletterSubscriptionRequest $request): JsonResponse
    {
        NewsletterSubscriber::create([
            'email' => $request->validated('email'),
            'is_subscribed' => true,
        ]);

        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'You have successfully subscribed to our newsletter.',
            ]);
        }

        return back()->with('success', 'You have successfully subscribed to our newsletter.');
    }
}
