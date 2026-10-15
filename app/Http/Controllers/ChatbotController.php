<?php

namespace App\Http\Controllers;

use App\Models\ChatbotQuery;
use App\Services\ChatbotMatchingService;
use App\Support\NotifyAdmins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Handle an incoming chatbot message.
     *
     * The message is scored word-by-word against stored chatbot queries and
     * the best match wins. When nothing matches, the query is stored with a
     * null answer (only if it looks like a serious question) so an admin can
     * answer it later, and a friendly fallback reply is returned.
     */
    public function message(Request $request, ChatbotMatchingService $matcher): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        $message = trim($validated['message']);

        $match = $matcher->findBestMatch($message);

        if ($match) {
            return response()->json([
                'reply' => $match->answer,
                'matched' => true,
            ]);
        }

        // Store the unmatched query once (dedupe case-insensitively), but
        // only when it looks like a serious question — short, dummy and
        // small-talk messages are skipped.
        if ($matcher->shouldStore($message)) {
            $exists = ChatbotQuery::query()
                ->whereRaw('LOWER(`query`) = ?', [mb_strtolower($message)])
                ->exists();

            if (!$exists) {
                $query = ChatbotQuery::create([
                    'query' => $message,
                    'answer' => null,
                ]);

                NotifyAdmins::send(
                    'chatbot',
                    'New chatbot query',
                    "Unanswered: \"{$message}\"",
                    route('admin.chatbot.show', $query),
                );
            }
        }

        return response()->json([
            'reply' => "Thanks for reaching out! Our team will get back to you shortly. For an instant response, you can also reach us via WhatsApp.",
            'matched' => false,
        ]);
    }
}
