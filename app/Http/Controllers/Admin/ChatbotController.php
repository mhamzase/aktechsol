<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatbotQuery;
use App\Support\LogActivity;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatbotQuery::query()->latest();

        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('query', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        if ($request->query('filter') === 'unanswered') {
            $query->whereNull('answer');
        }

        $queries = $query->paginate($request->get('per_page', 10));

        $stats = [
            'total'      => ChatbotQuery::count(),
            'answered'   => ChatbotQuery::whereNotNull('answer')->count(),
            'unanswered' => ChatbotQuery::whereNull('answer')->count(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'rows'       => view('admin.chatbot._rows', ['queries' => $queries])->render(),
                'pagination' => view('admin.partials.ajax-pagination', ['items' => $queries])->render(),
                'stats'      => "Showing {$queries->firstItem()}–{$queries->lastItem()} of {$queries->total()}",
            ]);
        }

        return view('admin.chatbot.index', compact('queries', 'stats'));
    }

    public function show(ChatbotQuery $chatbotQuery)
    {
        if (request()->ajax() || request()->expectsJson()) {
            return view('admin.chatbot.show', ['query' => $chatbotQuery]);
        }

        return redirect()->route('admin.chatbot.index', ['view' => $chatbotQuery->id]);
    }

    public function update(Request $request, ChatbotQuery $chatbotQuery)
    {
        $validated = $request->validate([
            'answer' => 'nullable|string|max:20000',
        ]);

        $answer = trim($validated['answer'] ?? '');
        $chatbotQuery->update(['answer' => $answer !== '' ? $answer : null]);
        LogActivity::record('chatbot_answer_updated', $chatbotQuery, "Answered chatbot query #{$chatbotQuery->id}");

        $stats = [
            'total'      => ChatbotQuery::count(),
            'answered'   => ChatbotQuery::whereNotNull('answer')->count(),
            'unanswered' => ChatbotQuery::whereNull('answer')->count(),
        ];

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => 'Answer saved.', 'stats' => $stats]);
        }

        return back()->with('success', 'Answer saved.');
    }

    public function destroy(ChatbotQuery $chatbotQuery)
    {
        $chatbotQuery->delete();
        LogActivity::record('chatbot_query_deleted', null, "Deleted chatbot query");

        if (request()->ajax()) {
            return response()->json(['message' => 'Query deleted.']);
        }

        return redirect()->route('admin.chatbot.index')->with('success', 'Query deleted.');
    }
}
