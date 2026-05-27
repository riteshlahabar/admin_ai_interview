<?php

namespace App\Services;

use App\Exceptions\OpenAIRequestException;
use OpenAI\Exceptions\ErrorException;
use OpenAI\Exceptions\RateLimitException;
use OpenAI\Exceptions\TransporterException;
use OpenAI\Laravel\Facades\OpenAI;
use Throwable;

class InterviewAIService
{
    private const DEFAULT_MODEL = 'gpt-4o-mini';
    private const RETRY_DELAYS_MS = [600, 1200];

    public function generateAnswer(
        string $question,
        string $role,
        string $topic,
        string $level,
        string $language = 'en'
    ): array {
        $user =
            "Role: {$role}\n" .
            "Topic: {$topic}\n" .
            "Level: {$level}\n" .
            "Language: {$language}\n" .
            "Question: {$question}";

        $response = $this->chatWithRetry($this->buildChatPayload([
            'messages' => [
                [
                    'role' => 'system',
                    'content' =>
                        "You are a senior interviewer for {$role} on {$topic}. Reply in {$language}.\n" .
                        "Return ONLY JSON. Schema:\n" .
                        "{\n  \"type\": \"object\",\n  \"properties\": {\n    \"text\": {\"type\":\"string\"},\n    \"ssml\": {\"type\":[\"string\",\"null\"]}\n  },\n  \"required\":[\"text\",\"ssml\"],\n  \"additionalProperties\": false\n}\n" .
                        "Rules: 1) text must be a clear point-wise interview response using numbered points by default. " .
                        "2) Keep text plain: no bold markers, no headings, no code fences, no JSON braces inside text. " .
                        "3) ssml is <speak>...</speak> or null. 4) Do not include SSML in text.",
                ],
                ['role' => 'user', 'content' => $user],
            ],
            'temperature' => 0.4,
            'max_tokens' => 800,
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'interview_answer',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'text' => ['type' => 'string'],
                            'ssml' => ['type' => ['string', 'null']],
                        ],
                        'required' => ['text', 'ssml'],
                        'additionalProperties' => false,
                    ],
                    'strict' => true,
                ],
            ],
        ]));

        $content = $response->choices[0]->message->content ?? '{}';
        return $this->normalizeAnswer($content);
    }

    public function suggestQuestions(
        string $role,
        string $topic,
        string $level,
        string $language = 'en'
    ): array {
        $userPrompt =
            "Role: {$role}\n" .
            "Topic: {$topic}\n" .
            "Level: {$level}\n" .
            "Language: {$language}\n" .
            "Generate concise interview questions.";

        $response = $this->chatWithRetry($this->buildChatPayload([
            'messages' => [
                [
                    'role' => 'system',
                    'content' =>
                        "Return ONLY JSON. Schema:\n" .
                        "{\n  \"type\": \"object\",\n  \"properties\": {\n    \"suggestions\": {\n      \"type\": \"array\",\n      \"items\": {\"type\": \"string\"},\n      \"minItems\": 5,\n      \"maxItems\": 8\n    }\n  },\n  \"required\": [\"suggestions\"],\n  \"additionalProperties\": false\n}\n" .
                        "Rules: Return practical interview questions only.",
                ],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'temperature' => 0.6,
            'max_tokens' => 500,
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'interview_suggestions',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'suggestions' => [
                                'type' => 'array',
                                'items' => ['type' => 'string'],
                                'minItems' => 5,
                                'maxItems' => 8,
                            ],
                        ],
                        'required' => ['suggestions'],
                        'additionalProperties' => false,
                    ],
                    'strict' => true,
                ],
            ],
        ]));

        $content = $response->choices[0]->message->content ?? '{}';
        $decoded = json_decode($content, true);
        $rawList = is_array($decoded) && isset($decoded['suggestions']) && is_array($decoded['suggestions'])
            ? $decoded['suggestions']
            : $this->extractFallbackSuggestions($content);

        $cleaned = [];
        foreach ($rawList as $entry) {
            if (!is_string($entry)) {
                continue;
            }

            $line = trim($entry);
            if ($line === '') {
                continue;
            }

            $cleaned[] = $line;
        }

        $unique = array_values(array_unique($cleaned));
        return array_slice($unique, 0, 8);
    }

    private function chatWithRetry(array $payload): object
    {
        $attempt = 0;
        $maxAttempts = count(self::RETRY_DELAYS_MS) + 1;

        while ($attempt < $maxAttempts) {
            try {
                /** @var object $response */
                $response = OpenAI::chat()->create($payload);
                return $response;
            } catch (RateLimitException|ErrorException|TransporterException $e) {
                $status = $this->resolveStatusCode($e);
                $retryable = $this->isRetryable($e, $status);

                if ($retryable && $attempt < count(self::RETRY_DELAYS_MS)) {
                    usleep(self::RETRY_DELAYS_MS[$attempt] * 1000);
                    $attempt++;
                    continue;
                }

                throw $this->mapOpenAIException($e, $status);
            } catch (Throwable $e) {
                throw new OpenAIRequestException(
                    'AI service is unavailable right now. Please try again in a minute.',
                    503,
                    $e
                );
            }
        }

        throw new OpenAIRequestException(
            'AI service is unavailable right now. Please try again in a minute.',
            503
        );
    }

    private function resolveStatusCode(Throwable $e): int
    {
        if ($e instanceof RateLimitException) {
            return 429;
        }

        if ($e instanceof ErrorException) {
            return $e->getStatusCode();
        }

        return 503;
    }

    private function isRetryable(Throwable $e, int $status): bool
    {
        if ($e instanceof RateLimitException || $e instanceof TransporterException) {
            return true;
        }

        return in_array($status, [429, 500, 502, 503, 504], true);
    }

    private function mapOpenAIException(Throwable $e, int $status): OpenAIRequestException
    {
        if ($status === 429) {
            return new OpenAIRequestException(
                'OpenAI rate limit or quota exceeded. Please retry after a minute or check billing and usage limits.',
                429,
                $e
            );
        }

        if ($status === 401 || $status === 403) {
            return new OpenAIRequestException(
                'OpenAI API key is invalid or unauthorized for this project.',
                401,
                $e
            );
        }

        if ($status >= 400 && $status < 500) {
            return new OpenAIRequestException(
                'OpenAI request was rejected. Please verify API key, model access, and request data.',
                400,
                $e
            );
        }

        return new OpenAIRequestException(
            'OpenAI service failed temporarily. Please try again shortly.',
            503,
            $e
        );
    }

    private function normalizeAnswer(string $content): array
    {
        $cleanContent = $this->extractJsonCandidate($content);
        $decoded = json_decode($cleanContent, true);
        if (!is_array($decoded) || !array_key_exists('text', $decoded)) {
            return [
                'text' => $this->sanitizeAnswerText($content),
                'ssml' => (preg_match('/<\s*speak\b[\s\S]*?<\/\s*speak\s*>/i', $content, $matches) ? $matches[0] : null),
            ];
        }

        return [
            'text' => $this->sanitizeAnswerText((string) ($decoded['text'] ?? '')),
            'ssml' => isset($decoded['ssml']) && is_string($decoded['ssml']) ? $decoded['ssml'] : null,
        ];
    }

    private function extractJsonCandidate(string $content): string
    {
        $clean = trim($content);

        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/i', $clean, $matches)) {
            $clean = trim($matches[1]);
        }

        if (json_decode($clean, true) !== null) {
            return $clean;
        }

        if (preg_match('/\{[\s\S]*\}/', $clean, $matches)) {
            return trim($matches[0]);
        }

        return $clean;
    }

    private function sanitizeAnswerText(string $text): string
    {
        $clean = preg_replace('/<\s*speak\b[\s\S]*?<\/\s*speak\s*>/i', '', $text) ?? '';
        $clean = preg_replace('/```(?:json|markdown)?|```/i', '', $clean) ?? $clean;
        $clean = str_replace(['**', '__'], '', $clean);

        return trim($clean);
    }

    private function extractFallbackSuggestions(string $content): array
    {
        $lines = preg_split('/\r\n|\r|\n/', $content) ?: [];
        $items = [];

        foreach ($lines as $line) {
            $clean = trim(preg_replace('/^[0-9]+[\.\)]\s*/', '', (string) $line) ?? '');
            if ($clean !== '') {
                $items[] = $clean;
            }
        }

        return $items;
    }

    private function buildChatPayload(array $payload): array
    {
        $payload['model'] = $this->configuredModel();

        $openRouterFallbackModels = $this->openRouterFallbackModels();
        if (!empty($openRouterFallbackModels)) {
            $payload['models'] = $openRouterFallbackModels;
        }

        return $payload;
    }

    private function configuredModel(): string
    {
        $model = trim((string) env('AI_MODEL', self::DEFAULT_MODEL));
        return $model !== '' ? $model : self::DEFAULT_MODEL;
    }

    private function openRouterFallbackModels(): array
    {
        if (!$this->isOpenRouterBaseUrl()) {
            return [];
        }

        $raw = trim((string) env('OPENROUTER_FALLBACK_MODELS', ''));
        if ($raw === '') {
            return [];
        }

        $models = array_filter(array_map(
            static fn ($item) => trim((string) $item),
            explode(',', $raw)
        ));

        return array_values($models);
    }

    private function isOpenRouterBaseUrl(): bool
    {
        $baseUri = trim((string) config('openai.base_uri', ''));
        return $baseUri !== '' && str_contains(strtolower($baseUri), 'openrouter.ai');
    }
}
