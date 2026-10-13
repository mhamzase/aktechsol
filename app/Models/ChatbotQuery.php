<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ChatbotQuery — stored Q&A for the keyword chatbot.
 * The widget matches a visitor's message against stored queries;
 * unmatched messages are saved with a null answer (is_answered = false)
 * so the team can answer them manually from the admin panel.
 */
class ChatbotQuery extends Model
{
    protected $fillable = [
        'query', 'answer', 'is_answered', 'session_id',
    ];

    protected $casts = [
        'is_answered' => 'boolean',
    ];

    /**
     * Find the best stored answer for a visitor message.
     * Longest / most specific keyword match wins (case-insensitive).
     */
    public static function findAnswer(string $message): ?self
    {
        $message = mb_strtolower(trim($message));

        if ($message === '') {
            return null;
        }

        $best = null;
        $bestScore = 0;

        foreach (self::where('is_answered', true)->cursor() as $row) {
            $query = mb_strtolower($row->query);

            // Exact match always wins.
            if ($query === $message) {
                return $row;
            }

            // Score = number of shared significant words.
            $qWords = array_filter(
                preg_split('/\s+/', $query),
                fn ($w) => mb_strlen($w) > 2
            );
            $score = 0;
            foreach ($qWords as $word) {
                if (str_contains($message, $word)) {
                    $score++;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $row;
            }
        }

        return $bestScore > 0 ? $best : null;
    }
}
