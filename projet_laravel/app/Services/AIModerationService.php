<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIModerationService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('services.ai_moderation', []);
    }

    /**
     * Moderate content using AI (with rate limiting and conservative usage)
     *
     * @param string $content
     * @param bool $forceCheck Force AI check even if rate limited (for high-risk content)
     * @return array ['status' => 'approved|flagged|rejected', 'reason' => string, 'confidence' => float]
     */
    public function moderate(string $content, bool $forceCheck = false): array
    {
        if (!$this->isEnabled()) {
            \Log::debug('AI moderation disabled, returning approved');
            return [
                'status' => 'approved',
                'reason' => 'AI moderation disabled',
                'confidence' => 0.0
            ];
        }

        // Check rate limiting unless forced
        if (!$forceCheck && !$this->shouldCallAPI()) {
            \Log::info('AI moderation rate limited, using basic checks only');
            return [
                'status' => 'approved',
                'reason' => 'Rate limited - using basic moderation only',
                'confidence' => 0.0
            ];
        }

        \Log::info('Calling AI moderation API', [
            'provider' => $this->config['provider'] ?? 'groq',
            'forced' => $forceCheck,
            'content_preview' => substr($content, 0, 50) . (strlen($content) > 50 ? '...' : '')
        ]);

        try {
            $provider = $this->config['provider'] ?? 'groq';

            switch ($provider) {
                case 'groq':
                    return $this->moderateWithGROQ($content);
                case 'openai':
                    return $this->moderateWithOpenAI($content);
                case 'perspective':
                    return $this->moderateWithPerspective($content);
                default:
                    return $this->moderateWithCustom($content);
            }
        } catch (\Exception $e) {
            \Log::error('AI moderation failed, returning safe default', [
                'error' => $e->getMessage(),
                'provider' => $this->config['provider'] ?? 'unknown',
                'content_length' => strlen($content)
            ]);

            // Return neutral result on failure
            return [
                'status' => 'approved', // Default to approved on failure to avoid blocking legitimate content
                'reason' => 'Unable to verify content with AI',
                'confidence' => 0.0
            ];
        }
    }

    /**
     * Check if AI moderation is enabled
     */
    public function isEnabled(): bool
    {
        return $this->config['enabled'] ?? false;
    }

    /**
     * Check if we should call the AI API (rate limiting)
     */
    protected function shouldCallAPI(): bool
    {
        $maxDailyCalls = $this->config['max_daily_calls'] ?? 100;
        $callInterval = $this->config['call_interval'] ?? 60;

        $cacheKey = 'ai_moderation_call_count_' . date('Y-m-d');
        $lastCallKey = 'ai_moderation_last_call';

        $callCount = \Cache::get($cacheKey, 0);
        $lastCall = \Cache::get($lastCallKey, 0);

        // Check daily limit
        if ($callCount >= $maxDailyCalls) {
            return false;
        }

        // Check minimum interval between calls
        if (time() - $lastCall < $callInterval) {
            return false;
        }

        return true;
    }

    /**
     * Record an API call for rate limiting
     */
    protected function recordAPICall(): void
    {
        $cacheKey = 'ai_moderation_call_count_' . date('Y-m-d');
        $lastCallKey = 'ai_moderation_last_call';

        $callCount = \Cache::get($cacheKey, 0);
        \Cache::put($cacheKey, $callCount + 1, now()->endOfDay());
        \Cache::put($lastCallKey, time(), now()->addMinutes(5));
    }

    /**
     * Moderate with GROQ API
     */
    protected function moderateWithGROQ(string $content): array
    {
        $apiKey = $this->config['api_key'];
        $model = $this->config['model'] ?? 'llama-3.3-70b-versatile';

        $systemPrompt = "You are a strict content moderation AI. Analyze the following message and determine if it contains harmful, inappropriate, or violating content. 

Respond with ONLY a JSON object in this exact format:
{
  \"status\": \"approved\"|\"flagged\"|\"rejected\",
  \"reason\": \"brief explanation if flagged or rejected\",
  \"confidence\": 0.0-1.0
}

Guidelines:
- approved: Safe, appropriate content with no harmful intent
- flagged: Content that may need human review but is not clearly harmful
- rejected: Harmful, abusive, hateful, or violating content including:
  * Hate speech, racism, discrimination
  * Threats, violence, harassment
  * Suicide encouragement or self-harm
  * Illegal activities, spam, explicit content
- Be strict - reject any content that promotes harm, hate, or illegal activities
- Consider context and intent - reject even subtle harmful content
- Nazi references, Hitler praise, and similar hate content must be rejected";

        $response = Http::timeout($this->config['timeout'] ?? 10)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $content]
                ],
                'temperature' => 0.1, // Low temperature for consistent moderation
                'max_tokens' => 150,
                'response_format' => ['type' => 'json_object']
            ]);

        if (!$response->successful()) {
            throw new \Exception('GROQ API request failed: ' . $response->body());
        }

        $data = $response->json();

        if (!isset($data['choices'][0]['message']['content'])) {
            throw new \Exception('Invalid GROQ response format');
        }

        $content = $data['choices'][0]['message']['content'];

        // Parse the JSON response
        $result = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON response from GROQ: ' . $content);
        }

        // Record the API call
        $this->recordAPICall();

        \Log::info('GROQ API moderation result', [
            'status' => $result['status'] ?? 'flagged',
            'reason' => $result['reason'] ?? 'AI moderation response',
            'confidence' => $result['confidence'] ?? 0.5,
            'api_calls_today' => \Cache::get('ai_moderation_call_count_' . date('Y-m-d'), 0)
        ]);

        return [
            'status' => $result['status'] ?? 'flagged',
            'reason' => $result['reason'] ?? 'AI moderation response',
            'confidence' => $result['confidence'] ?? 0.5
        ];
    }

    /**
     * Moderate with OpenAI Moderation API
     */
    protected function moderateWithOpenAI(string $content): array
    {
        $apiKey = $this->config['api_key'];

        $response = Http::timeout($this->config['timeout'] ?? 5)
            ->post('https://commentanalyzer.googleapis.com/v1alpha1/comments:analyze', [
                'comment' => ['text' => $content],
                'languages' => ['en', 'fr'],
                'requestedAttributes' => [
                    'TOXICITY' => new \stdClass(),
                    'SEVERE_TOXICITY' => new \stdClass(),
                    'IDENTITY_ATTACK' => new \stdClass(),
                    'INSULT' => new \stdClass(),
                    'THREAT' => new \stdClass(),
                ],
                'key' => $apiKey,
            ]);

        if (!$response->successful()) {
            throw new \Exception('Perspective API request failed: ' . $response->body());
        }

        $data = $response->json();

        if (!isset($data['attributeScores'])) {
            throw new \Exception('Invalid Perspective API response');
        }

        $scores = $data['attributeScores'];
        $maxScore = 0;
        $maxAttribute = '';

        foreach ($scores as $attribute => $scoreData) {
            $score = $scoreData['summaryScore']['value'];
            if ($score > $maxScore) {
                $maxScore = $score;
                $maxAttribute = $attribute;
            }
        }

        // Threshold for flagging (adjustable)
        $threshold = 0.7;

        if ($maxScore >= $threshold) {
            return [
                'status' => $maxScore >= 0.9 ? 'rejected' : 'flagged',
                'reason' => "Content flagged for {$maxAttribute} (score: " . round($maxScore, 2) . ")",
                'confidence' => $maxScore,
                'attribute' => $maxAttribute
            ];
        }

        return [
            'status' => 'approved',
            'reason' => null,
            'confidence' => 1.0 - $maxScore
        ];
    }

    /**
     * Custom AI moderation implementation
     */
    protected function moderateWithCustom(string $content): array
    {
        $endpoint = $this->config['endpoint'];

        if (!$endpoint) {
            throw new \Exception('Custom AI endpoint not configured');
        }

        $response = Http::timeout($this->config['timeout'] ?? 5)
            ->withHeaders([
                'Authorization' => 'Bearer ' . ($this->config['api_key'] ?? ''),
                'Content-Type' => 'application/json',
            ])
            ->post($endpoint, [
                'content' => $content,
                'context' => 'chat_message'
            ]);

        if (!$response->successful()) {
            throw new \Exception('Custom AI API request failed: ' . $response->body());
        }

        $data = $response->json();

        // Assume custom API returns similar format
        return [
            'status' => $data['status'] ?? 'flagged',
            'reason' => $data['reason'] ?? 'Flagged by custom AI',
            'confidence' => $data['confidence'] ?? 0.5
        ];
    }

    /**
     * Calculate confidence score from OpenAI response
     */
    protected function calculateOpenAIConfidence(array $result): float
    {
        $scores = $result['category_scores'] ?? [];
        $maxScore = 0;

        foreach ($scores as $score) {
            $maxScore = max($maxScore, $score);
        }

        return $maxScore;
    }

    /**
     * Get moderation statistics
     */
    public function getStats(): array
    {
        // This could track moderation metrics
        return [
            'enabled' => $this->isEnabled(),
            'provider' => $this->config['provider'] ?? 'none',
            'endpoint' => $this->config['endpoint'] ?? null,
        ];
    }
}