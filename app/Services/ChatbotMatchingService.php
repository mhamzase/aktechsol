<?php

namespace App\Services;

use App\Models\ChatbotQuery;

/**
 * Word-by-word keyword matching for the front-end chatbot.
 *
 * Each stored query is tokenized into meaningful keywords (lowercased,
 * punctuation stripped, stop-words removed, lightly stemmed). The
 * visitor's message is tokenized the same way, and every answered query
 * is scored by the fraction of its keywords found in the message —
 * tolerating small typos via Levenshtein distance. The highest-scoring
 * query wins (ties go to the longer, more specific query). A minimum
 * score of 0.5 keeps weak matches from producing wrong answers.
 *
 * Matching is fully case- and punctuation-insensitive. Queries made up
 * only of stop-words (e.g. "hello", "thank you") match on their
 * normalized full text instead of being skipped.
 */
class ChatbotMatchingService
{
    /**
     * Minimum score (0-1) for a match to be accepted.
     */
    private const MIN_SCORE = 0.5;

    /**
     * Minimum message length (chars) for a message to be worth storing
     * as an unanswered query for the admin.
     */
    private const MIN_STORE_LENGTH = 10;

    /**
     * Common words ignored while scoring so "what is the fee" still
     * matches a stored "fee" keyword.
     */
    private const STOP_WORDS = [
        'a', 'an', 'the', 'is', 'are', 'was', 'were', 'be', 'been', 'being',
        'i', 'me', 'my', 'we', 'our', 'you', 'your', 'he', 'she', 'it', 'they', 'their',
        'this', 'that', 'these', 'those', 'what', 'which', 'who', 'whom', 'whose',
        'how', 'when', 'where', 'why', 'do', 'does', 'did', 'done', 'doing',
        'can', 'could', 'shall', 'should', 'will', 'would', 'may', 'might', 'must',
        'and', 'or', 'but', 'if', 'then', 'else', 'of', 'in', 'on', 'at', 'to', 'for',
        'with', 'by', 'from', 'as', 'about', 'into', 'over', 'after', 'before',
        'please', 'kindly', 'sir', 'madam', 'dear', 'hi', 'hello', 'hey', 'salam',
        'aoa', 'assalam', 'alaikum', 'thanks', 'thank', 'shukriya',
    ];

    /**
     * Short conversational messages that get the fallback reply but are
     * never stored as unanswered queries.
     */
    private const SMALL_TALK = [
        'hi', 'hello', 'hey', 'salam', 'aoa', 'assalam o alaikum', 'assalamualaikum',
        'good morning', 'good afternoon', 'good evening',
        'thanks', 'thank you', 'thankyou', 'shukriya', 'ok', 'okay', 'okayy',
        'bye', 'allah hafiz', 'khuda hafiz', 'yes', 'no', 'hmm', 'acha',
    ];

    /**
     * Find the best matching answered query for a visitor message.
     */
    public function findBestMatch(string $message): ?ChatbotQuery
    {
        $candidates = ChatbotQuery::query()
            ->whereNotNull('answer')
            ->get(['id', 'query', 'answer']);

        // Exact normalized match first — an explicitly stored query like
        // "hello" wins even when the message holds only stop-words
        // (which otherwise never reach the keyword scoring below).
        $normalizedMessage = $this->normalize($message);

        if ($normalizedMessage !== '') {
            foreach ($candidates as $candidate) {
                $normalizedCandidate = $this->normalize((string) $candidate->query);

                if ($normalizedCandidate !== '' && $normalizedCandidate === $normalizedMessage) {
                    return $candidate;
                }
            }
        }

        $messageWords = $this->words($message);

        if ($messageWords === []) {
            return null;
        }

        $best = null;
        $bestScore = 0.0;
        $bestWordCount = 0;

        foreach ($candidates as $candidate) {
            $candidateQuery = (string) $candidate->query;
            $queryWords = $this->words($candidateQuery);

            $score = $this->score($messageWords, $queryWords, $message, $candidateQuery);

            if ($score > $bestScore || ($score === $bestScore && $score > 0 && count($queryWords) > $bestWordCount)) {
                $bestScore = $score;
                $best = $candidate;
                $bestWordCount = count($queryWords);
            }
        }

        return $bestScore >= self::MIN_SCORE ? $best : null;
    }

    /**
     * Decide whether an unmatched message is a serious query worth storing
     * for the admin. Short, dummy, numeric-only and small-talk messages
     * are skipped.
     */
    public function shouldStore(string $message): bool
    {
        $trimmed = trim($message);

        if (mb_strlen($trimmed) < self::MIN_STORE_LENGTH) {
            return false;
        }

        if ($this->words($trimmed) === []) {
            return false;
        }

        // Must contain letters — not just numbers, punctuation or emoji.
        if (!preg_match('/\p{L}{2,}/u', $trimmed)) {
            return false;
        }

        // Greetings and small talk are never stored.
        $normalized = mb_strtolower(preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $trimmed) ?? '');
        $normalized = trim(preg_replace('/\s+/', ' ', $normalized) ?? '');

        return !in_array($normalized, self::SMALL_TALK, true);
    }

    /**
     * Score how well a stored query matches the visitor message (0-1).
     */
    private function score(array $messageWords, array $queryWords, string $message, string $query): float
    {
        $normalizedMessage = $this->normalize($message);
        $normalizedQuery = $this->normalize($query);

        // Exact normalized match — lets stop-word-only queries like
        // "hello" or "thank you" match their answer instead of being skipped.
        if ($normalizedQuery !== '' && $normalizedQuery === $normalizedMessage) {
            return 1.0;
        }

        // The full stored phrase appearing in the message is a perfect match.
        $looseQuery = mb_strtolower(trim(preg_replace('/\s+/', ' ', $query) ?? ''));

        if ($looseQuery !== '' && str_contains(mb_strtolower($message), $looseQuery)) {
            return 1.0;
        }

        if ($queryWords === [] || $messageWords === []) {
            return 0.0;
        }

        // Fraction of the stored query's keywords found in the message,
        // tolerating small typos.
        return $this->countHits($queryWords, $messageWords) / count($queryWords);
    }

    /**
     * Count keyword hits, tolerating small typos. Each stored keyword
     * consumes at most one message word.
     */
    private function countHits(array $queryWords, array $messageWords): int
    {
        $hits = 0;
        $used = [];

        foreach ($queryWords as $queryWord) {
            foreach ($messageWords as $i => $messageWord) {
                if (isset($used[$i])) {
                    continue;
                }

                if ($messageWord === $queryWord || $this->isFuzzyMatch($queryWord, $messageWord)) {
                    $hits++;
                    $used[$i] = true;
                    break;
                }
            }
        }

        return $hits;
    }

    /**
     * True when two keywords differ by no more than a typo or two.
     * Short words must match exactly to avoid false positives.
     */
    private function isFuzzyMatch(string $a, string $b): bool
    {
        $len = max(mb_strlen($a), mb_strlen($b));

        if ($len < 4) {
            return false;
        }

        $maxDistance = $len >= 7 ? 2 : 1;

        // Skip the edit-distance computation when the length difference
        // alone already rules a match out.
        if (abs(mb_strlen($a) - mb_strlen($b)) > $maxDistance) {
            return false;
        }

        return $this->wordDistance($a, $b) <= $maxDistance;
    }

    /**
     * Edit distance between two words (multibyte-safe).
     */
    private function wordDistance(string $a, string $b): int
    {
        // Fast C implementation for plain ASCII.
        if (!preg_match('/[^\x00-\x7F]/', $a . $b)) {
            $distance = levenshtein($a, $b);

            // levenshtein() returns -1 for strings over 255 chars —
            // treat those as too different to match.
            return $distance < 0 ? PHP_INT_MAX : $distance;
        }

        $aChars = mb_str_split($a);
        $bChars = mb_str_split($b);
        $aLen = count($aChars);
        $bLen = count($bChars);

        if ($aLen === 0) {
            return $bLen;
        }

        if ($bLen === 0) {
            return $aLen;
        }

        $prev = range(0, $bLen);

        for ($i = 1; $i <= $aLen; $i++) {
            $curr = [$i];

            for ($j = 1; $j <= $bLen; $j++) {
                $cost = $aChars[$i - 1] === $bChars[$j - 1] ? 0 : 1;
                $curr[$j] = min($prev[$j] + 1, $curr[$j - 1] + 1, $prev[$j - 1] + $cost);
            }

            $prev = $curr;
        }

        return $prev[$bLen];
    }

    /**
     * Lowercase, strip punctuation and collapse whitespace — for
     * case/punctuation-insensitive exact comparisons.
     */
    private function normalize(string $text): string
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text) ?? '';

        return trim(preg_replace('/\s+/', ' ', $text) ?? '');
    }

    /**
     * Normalize text into meaningful keyword tokens.
     *
     * @return list<string>
     */
    private function words(string $text): array
    {
        $text = mb_strtolower($text);
        $text = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $text) ?? '';
        $tokens = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        $words = [];
        foreach ($tokens as $token) {
            if (mb_strlen($token) < 2) {
                continue;
            }
            if (in_array($token, self::STOP_WORDS, true)) {
                continue;
            }
            $words[] = $this->stem($token);
        }

        return array_values(array_unique($words));
    }

    /**
     * Very light English stemming so plurals and verb forms match
     * ("courses"/"course", "starting"/"start"). ASCII-only; other
     * scripts pass through untouched.
     */
    private function stem(string $word): string
    {
        if (preg_match('/[^\x00-\x7F]/', $word)) {
            return $word;
        }

        $len = strlen($word);

        if ($len > 4 && str_ends_with($word, 'ies')) {
            return substr($word, 0, -3) . 'y'; // stories -> story
        }

        if ($len > 5 && (str_ends_with($word, 'sses') || str_ends_with($word, 'xes') || str_ends_with($word, 'zes') || str_ends_with($word, 'ches') || str_ends_with($word, 'shes'))) {
            return substr($word, 0, -2); // classes -> class, boxes -> box
        }

        if ($len > 3 && str_ends_with($word, 's') && !str_ends_with($word, 'ss') && !str_ends_with($word, 'us')) {
            return substr($word, 0, -1); // courses -> course, fees -> fee
        }

        if ($len > 5 && str_ends_with($word, 'ing')) {
            $stem = substr($word, 0, -3);

            if (strlen($stem) > 2 && $stem[-1] === $stem[-2]) {
                $stem = substr($stem, 0, -1); // running -> run
            }

            return $stem; // starting -> start
        }

        if ($len > 4 && str_ends_with($word, 'ed') && !str_ends_with($word, 'eed')) {
            return substr($word, 0, -2); // started -> start
        }

        return $word;
    }
}
