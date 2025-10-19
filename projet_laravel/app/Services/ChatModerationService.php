<?php

namespace App\Services;

class ChatModerationService
{
    protected $aiModerationService;

    public function __construct(AIModerationService $aiModerationService = null)
    {
        // Try to resolve AI service if not injected
        if ($aiModerationService === null) {
            try {
                $aiModerationService = app(AIModerationService::class);
            } catch (\Exception $e) {
                \Log::warning('Could not resolve AIModerationService: ' . $e->getMessage());
            }
        }

        $this->aiModerationService = $aiModerationService;
    }
    // Banned words - automatic rejection
    private $bannedWords = [
        // English banned words
        'fuck', 'shit', 'bitch', 'asshole', 'bastard', 'cunt', 'pussy', 'dick', 'cock', 'whore',
        'slut', 'rape', 'murder', 'suicide', 'terrorist', 'bomb', 'nigger', 'faggot',
        
        // French banned words
        'merde', 'putain', 'salope', 'connard', 'enculé', 'bordel', 'connerie', 'abruti',
        'imbécile', 'crétin', 'débile', 'nègre', 'pédé', 'chatte', 'bite', 'queue', 'pute', 'salopard',
        'viol', 'meurtre', 'suicide', 'terroriste', 'bombe'
    ];

    // Offensive words list (expandable) - English and French - flagged for review
    private $offensiveWords = [
        // English offensive words (less severe)
        'damn', 'crap', 'idiot', 'stupid', 'dumb', 'hate', 'kill', 'die', 'spam',
        
        // French offensive words (less severe)
        'haïr', 'tuer', 'mourir'
    ];

    // Spam patterns
    private $spamPatterns = [
        '/http[s]?:\/\/[^\s]+/',  // URLs
        '/www\.[^\s]+/',           // WWW links
        '/\b(\w+)\s+\1\s+\1/i',   // Repeated words (3+ times)
        '/(.)\1{4,}/',             // Repeated characters
        '/[A-Z]{5,}/',             // EXCESSIVE CAPS
        '/(buy|click|visit|check out|free|win|prize|discount|acheter|cliquer|visiter|gratuit|gagner|prix|remise)/i', // Spam keywords (English + French)
        '/\b\d{10,}\b/',           // Long numbers (potentially phone numbers)
        '/[^\w\s]{5,}/',           // Excessive special characters
        '/(.{50,}?)\1{2,}/',       // Repeated phrases
    ];

    /**
     * Moderate a chat message using AI-like rules with banned words and AI integration
     *
     * @param string $message
     * @return array ['approved' => bool, 'reason' => string|null, 'status' => string]
     */
    public function moderateMessage(string $message): array
    {
        $message = trim($message);

        \Log::debug('Starting message moderation', [
            'message_length' => strlen($message),
            'message_preview' => substr($message, 0, 30) . (strlen($message) > 30 ? '...' : '')
        ]);

        // Check if message is empty
        if (empty($message)) {
            \Log::info('Message rejected: empty content');
            $this->recordMessageMetrics('rejected', false);
            return [
                'approved' => false,
                'reason' => 'Votre message semble vide. Veuillez écrire quelque chose avant d\'envoyer.',
                'status' => 'rejected'
            ];
        }

        // Check message length
        if (strlen($message) < 2) {
            \Log::info('Message rejected: too short', ['length' => strlen($message)]);
            $this->recordMessageMetrics('rejected', false);
            return [
                'approved' => false,
                'reason' => 'Votre message est trop court. Essayez d\'écrire au moins quelques mots.',
                'status' => 'rejected'
            ];
        }

        if (strlen($message) > 1000) {
            \Log::info('Message rejected: too long', ['length' => strlen($message)]);
            $this->recordMessageMetrics('rejected', false);
            return [
                'approved' => false,
                'reason' => 'Votre message est trop long (maximum 1000 caractères). Veuillez le raccourcir.',
                'status' => 'rejected'
            ];
        }

        // Check for banned words - automatic rejection
        $bannedCheck = $this->checkBannedWords($message);
        if (!$bannedCheck['clean']) {
            $foundWords = implode(', ', $bannedCheck['found']);
            \Log::warning('Message rejected: banned words found', [
                'banned_words' => $foundWords,
                'message_preview' => substr($message, 0, 50) . '...'
            ]);

            $this->recordMessageMetrics('rejected', false);
            return [
                'approved' => false,
                'reason' => 'Votre message contient des mots interdits : "' . $foundWords . '". Ce type de langage n\'est pas autorisé.',
                'status' => 'rejected'
            ];
        }

        // Check for offensive words - flagged for review
        $offensiveCheck = $this->checkOffensiveContent($message);
        if (!$offensiveCheck['clean']) {
            \Log::info('Message flagged: offensive content', [
                'offensive_words' => implode(', ', $offensiveCheck['found'])
            ]);

            $this->recordMessageMetrics('flagged', false);
            return [
                'approved' => true,
                'reason' => 'Votre message contient des termes potentiellement offensants. Il sera signalé pour modération.',
                'status' => 'flagged'
            ];
        }

        // Check for spam patterns
        $spamCheck = $this->checkSpamPatterns($message);
        if ($spamCheck['isSpam']) {
            \Log::warning('Message rejected: spam pattern detected', [
                'spam_reason' => $spamCheck['reason']
            ]);

            $this->recordMessageMetrics('rejected', false);
            return [
                'approved' => false,
                'reason' => $this->getSpamMessage($spamCheck['reason']),
                'status' => 'rejected'
            ];
        }

        // Check for potential harassment (excessive mentions)
        if (preg_match_all('/@\w+/', $message, $mentions)) {
            if (count($mentions[0]) > 3) {
                $this->recordMessageMetrics('flagged', false);
                return [
                    'approved' => true,
                    'reason' => 'Vous mentionnez beaucoup de personnes. Assurez-vous que c\'est nécessaire.',
                    'status' => 'flagged'
                ];
            }
        }

        // Check for coded language or leetspeak attempts to bypass filters
        if ($this->containsLeetspeak($message)) {
            $this->recordMessageMetrics('rejected', false);
            return [
                'approved' => false,
                'reason' => 'Il semble que vous essayez de contourner nos filtres. Veuillez utiliser un langage normal.',
                'status' => 'rejected'
            ];
        }

        // Check for excessive emojis
        $emojiCount = preg_match_all('/[\x{1F600}-\x{1F64F}]/u', $message);
        if ($emojiCount > 10) {
            $this->recordMessageMetrics('flagged', false);
            return [
                'approved' => true,
                'reason' => 'Votre message contient beaucoup d\'émojis. Il sera signalé pour modération.',
                'status' => 'flagged'
            ];
        }

        // For messages that pass basic checks but might be borderline, use AI moderation sparingly
        // Only call AI for messages that contain potentially sensitive content
        if ($this->shouldUseAIModeration($message)) {
            \Log::info('AI moderation triggered for message', [
                'message_preview' => substr($message, 0, 50) . (strlen($message) > 50 ? '...' : ''),
                'reason' => 'contains sensitive content or meets AI criteria'
            ]);

            $aiResult = $this->moderateWithAI($message, false); // Don't force - respect rate limits
            if ($aiResult['status'] === 'rejected') {
                \Log::warning('Message rejected by AI during initial check', [
                    'reason' => $aiResult['reason'],
                    'confidence' => $aiResult['confidence'] ?? 0.5
                ]);

                $this->recordMessageMetrics('rejected', true);
                return [
                    'approved' => false,
                    'reason' => $aiResult['reason'] ?? 'Votre message a été rejeté par notre système de modération IA.',
                    'status' => 'rejected'
                ];
            } elseif ($aiResult['status'] === 'flagged') {
                \Log::info('Message flagged by AI during initial check', [
                    'reason' => $aiResult['reason'],
                    'confidence' => $aiResult['confidence'] ?? 0.5
                ]);

                $this->recordMessageMetrics('flagged', true);
                return [
                    'approved' => true,
                    'reason' => $aiResult['reason'] ?? 'Votre message sera examiné par nos modérateurs.',
                    'status' => 'flagged'
                ];
            } else {
                \Log::info('Message approved by AI during initial check', [
                    'confidence' => $aiResult['confidence'] ?? 0.5
                ]);
            }
        }

        // Message is clean
        $aiTriggered = $this->shouldUseAIModeration($message);
        \Log::info('Message approved: passed all basic moderation checks', [
            'message_length' => strlen($message),
            'will_use_ai' => $aiTriggered
        ]);

        // Record metrics
        $this->recordMessageMetrics('approved', $aiTriggered);

        return [
            'approved' => true,
            'reason' => null,
            'status' => 'approved'
        ];
    }

    /**
     * Check if message should use AI moderation (conservative approach)
     *
     * @param string $message
     * @return bool
     */
    private function shouldUseAIModeration(string $message): bool
    {
        // Only use AI for messages that are truly ambiguous or potentially harmful
        $lowerMessage = strtolower($message);

        // HIGH PRIORITY: Strong indicators of sensitive content (whole words only)
        $highRiskIndicators = [
            'violence', 'death', 'abuse', 'harm', 'suicide', 'drugs', 'alcohol',
            'mort', 'abus', 'mal', 'suicide', 'drogues', 'alcool',
            'kill', 'die', 'hurt', 'tuer', 'mourir', 'blesser',
            'racism', 'raciste', 'hate', 'haine', 'discrimination',
            'sex', 'sexe', 'porn', 'porno', 'nude', 'nudité'
        ];

        foreach ($highRiskIndicators as $indicator) {
            // Use word boundaries to avoid false positives (e.g., "mal" in "normal")
            if (preg_match('/\b' . preg_quote($indicator, '/') . '\b/u', $lowerMessage)) {
                \Log::info('AI moderation triggered: high-risk keyword detected', [
                    'keyword' => $indicator,
                    'message_length' => strlen($message)
                ]);
                return true;
            }
        }

        // MEDIUM PRIORITY: Very long messages that might contain complex content
        if (strlen($message) > 300) {
            \Log::info('AI moderation triggered: very long message', [
                'message_length' => strlen($message)
            ]);
            return true;
        }

        // MEDIUM PRIORITY: Messages with multiple complex sentences
        $sentenceCount = substr_count($message, '.') + substr_count($message, '!') + substr_count($message, '?');
        if ($sentenceCount > 3) {
            \Log::info('AI moderation triggered: complex multi-sentence message', [
                'sentence_count' => $sentenceCount,
                'message_length' => strlen($message)
            ]);
            return true;
        }

        // LOW PRIORITY: Ambiguous content indicators (only if combined with other factors)
        $ambiguousIndicators = [
            'maybe', 'perhaps', 'might', 'could', 'possibly',
            'peut-être', 'possiblement', 'éventuellement',
            'confused', 'confus', 'unclear', 'pas clair',
            'weird', 'étrange', 'strange', 'bizarre'
        ];

        $hasAmbiguous = false;
        foreach ($ambiguousIndicators as $indicator) {
            // Use word boundaries for ambiguous indicators too
            if (preg_match('/\b' . preg_quote($indicator, '/') . '\b/u', $lowerMessage)) {
                $hasAmbiguous = true;
                break;
            }
        }

        // Only trigger AI for ambiguous content if message is moderately long
        if ($hasAmbiguous && strlen($message) > 150) {
            \Log::info('AI moderation triggered: ambiguous content in longer message', [
                'message_length' => strlen($message),
                'has_ambiguous_language' => true
            ]);
            return true;
        }

        // Log that AI was NOT triggered for monitoring
        \Log::debug('AI moderation skipped: message passed basic checks', [
            'message_length' => strlen($message),
            'sentence_count' => $sentenceCount,
            'has_ambiguous_language' => $hasAmbiguous
        ]);

        return false;
    }

    /**
     * Get moderation statistics and metrics
     *
     * @return array
     */
    public function getModerationStats(): array
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        return [
            'ai_api' => [
                'calls_today' => \Cache::get('ai_moderation_call_count_' . $today, 0),
                'calls_yesterday' => \Cache::get('ai_moderation_call_count_' . $yesterday, 0),
                'last_call' => \Cache::get('ai_moderation_last_call', 0),
                'rate_limited_today' => \Cache::get('ai_moderation_rate_limited_' . $today, 0),
            ],
            'messages_processed' => [
                'total_today' => \Cache::get('chat_messages_processed_' . $today, 0),
                'ai_triggered_today' => \Cache::get('chat_messages_ai_triggered_' . $today, 0),
                'rejected_today' => \Cache::get('chat_messages_rejected_' . $today, 0),
                'flagged_today' => \Cache::get('chat_messages_flagged_' . $today, 0),
            ],
            'efficiency' => [
                'ai_usage_rate' => $this->calculateUsageRate($today),
                'rejection_rate' => $this->calculateRejectionRate($today),
            ]
        ];
    }

    /**
     * Calculate AI usage rate (percentage of messages that trigger AI)
     */
    private function calculateUsageRate(string $date): float
    {
        $total = \Cache::get('chat_messages_processed_' . $date, 0);
        $aiTriggered = \Cache::get('chat_messages_ai_triggered_' . $date, 0);

        return $total > 0 ? round(($aiTriggered / $total) * 100, 2) : 0.0;
    }

    /**
     * Calculate rejection rate (percentage of messages rejected)
     */
    private function calculateRejectionRate(string $date): float
    {
        $total = \Cache::get('chat_messages_processed_' . $date, 0);
        $rejected = \Cache::get('chat_messages_rejected_' . $date, 0);

        return $total > 0 ? round(($rejected / $total) * 100, 2) : 0.0;
    }

    /**
     * Record message processing metrics
     */
    private function recordMessageMetrics(string $status, bool $aiTriggered): void
    {
        $today = date('Y-m-d');

        // Increment total messages processed
        \Cache::increment('chat_messages_processed_' . $today);

        // Increment AI triggered counter
        if ($aiTriggered) {
            \Cache::increment('chat_messages_ai_triggered_' . $today);
        }

        // Increment status counters
        switch ($status) {
            case 'rejected':
                \Cache::increment('chat_messages_rejected_' . $today);
                break;
            case 'flagged':
                \Cache::increment('chat_messages_flagged_' . $today);
                break;
        }
    }

    /**
     * Perform AI moderation on flagged messages after they are sent
     * This is called asynchronously to avoid blocking message sending
     *
     * @param string $message
     * @return array|null Returns moderation result or null if not moderated
     */
    public function moderateFlaggedMessage(string $message): ?array
    {
        // Only moderate flagged messages with AI
        if (!$this->aiModerationService) {
            \Log::warning('AI moderation service not available for flagged message processing');
            return null;
        }

        \Log::info('Starting AI moderation for flagged message', [
            'message_preview' => substr($message, 0, 50) . (strlen($message) > 50 ? '...' : '')
        ]);

        try {
            // Force check for flagged messages (bypass rate limiting for important cases)
            $aiResult = $this->aiModerationService->moderate($message, true);

            \Log::info('AI moderation completed for flagged message', [
                'status' => $aiResult['status'],
                'reason' => $aiResult['reason'],
                'confidence' => $aiResult['confidence'] ?? 0.5
            ]);

            if ($aiResult['status'] === 'rejected') {
                \Log::warning('AI determined message should be DELETED', [
                    'reason' => $aiResult['reason'],
                    'confidence' => $aiResult['confidence'] ?? 0.5
                ]);

                return [
                    'action' => 'delete',
                    'reason' => $aiResult['reason'] ?? 'Message rejected by AI moderation',
                    'confidence' => $aiResult['confidence'] ?? 0.0
                ];
            }

            \Log::info('AI determined message should be KEPT', [
                'status' => $aiResult['status'],
                'reason' => $aiResult['reason'],
                'confidence' => $aiResult['confidence'] ?? 0.5
            ]);

            return [
                'action' => 'keep',
                'status' => $aiResult['status'],
                'reason' => $aiResult['reason'] ?? null,
                'confidence' => $aiResult['confidence'] ?? 0.0
            ];
        } catch (\Exception $e) {
            \Log::error('Post-send AI moderation failed', [
                'error' => $e->getMessage(),
                'message_length' => strlen($message),
                'message_preview' => substr($message, 0, 50) . (strlen($message) > 50 ? '...' : '')
            ]);

            return null; // Don't take action on failure
        }
    }

    /**
     * Check for banned words in the message
     *
     * @param string $message
     * @return array ['clean' => bool, 'found' => array]
     */
    private function checkBannedWords(string $message): array
    {
        $lowerMessage = strtolower($message);
        $foundWords = [];

        foreach ($this->bannedWords as $word) {
            // Use word boundaries to avoid false positives
            if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $lowerMessage)) {
                $foundWords[] = $word;
            }
        }

        return [
            'clean' => empty($foundWords),
            'found' => $foundWords
        ];
    }

    /**
     * Check for offensive content (less severe words)
     *
     * @param string $message
     * @return array
     */
    private function checkOffensiveContent(string $message): array
    {
        $lowerMessage = strtolower($message);
        $foundWords = [];

        foreach ($this->offensiveWords as $word) {
            // Use word boundaries to avoid false positives
            if (preg_match('/\b' . preg_quote($word, '/') . '\b/i', $lowerMessage)) {
                $foundWords[] = $word;
            }
        }

        return [
            'clean' => empty($foundWords),
            'found' => $foundWords
        ];
    }

    /**
     * Check for spam patterns in the message
     *
     * @param string $message
     * @return array ['isSpam' => bool, 'reason' => string]
     */
    private function checkSpamPatterns(string $message): array
    {
        foreach ($this->spamPatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                // Determine the reason based on the pattern
                $reason = 'Spam pattern detected';
                if (strpos($pattern, 'http') !== false || strpos($pattern, 'www') !== false) {
                    $reason = 'URL';
                } elseif (strpos($pattern, '(\w+)\s+\1\s+\1') !== false) {
                    $reason = 'Repeated words';
                } elseif (strpos($pattern, '(.)\1{4,}') !== false) {
                    $reason = 'Repeated characters';
                } elseif (strpos($pattern, '[A-Z]{5,}') !== false) {
                    $reason = 'Excessive caps';
                } elseif (strpos($pattern, 'buy|click|visit') !== false) {
                    $reason = 'Spam keywords';
                } elseif (strpos($pattern, '\d{10,}') !== false) {
                    $reason = 'Suspicious number sequence';
                } elseif (strpos($pattern, '[^\w\s]{5,}') !== false) {
                    $reason = 'Excessive special characters';
                } elseif (strpos($pattern, '(.{50,}?)\1{2,}') !== false) {
                    $reason = 'Repeated phrases';
                }

                return [
                    'isSpam' => true,
                    'reason' => $reason
                ];
            }
        }

        return [
            'isSpam' => false,
            'reason' => ''
        ];
    }

    /**
     * Get contextual message for offensive words
     *
     * @param string $foundWords
     * @return string
     */
    private function getOffensiveWordMessage(string $foundWords): string
    {
        $messages = [
            'Nous avons détecté des mots inappropriés dans votre message : "' . $foundWords . '". ' .
            'Veuillez reformuler votre message de manière respectueuse. ' .
            'Rappelons que notre communauté valorise le respect mutuel.',

            'Votre message contient des termes offensants : "' . $foundWords . '". ' .
            'Pour maintenir un environnement agréable, nous vous demandons de choisir vos mots avec soin.',

            'Les mots "' . $foundWords . '" ne sont pas acceptés dans nos discussions. ' .
            'Essayez de vous exprimer différemment pour que tout le monde se sente à l\'aise.',

            'Attention : votre message utilise un langage inapproprié ("' . $foundWords . '"). ' .
            'Notre modération veille à ce que les échanges restent courtois et bienveillants.'
        ];

        return $messages[array_rand($messages)];
    }

    /**
     * Get contextual message for spam patterns
     *
     * @param string $reason
     * @return string
     */
    private function getSpamMessage(string $reason): string
    {
        $messages = [
            'URL' => 'Les liens externes sont limités pour éviter le spam. Votre message sera vérifié par notre équipe.',
            'website link' => 'Les liens vers des sites web sont surveillés. Votre message est en cours de modération.',
            'Repeated words' => 'Votre message contient des répétitions. Il sera signalé pour vérification.',
            'Repeated characters' => 'Les caractères répétés peuvent être considérés comme du spam. Message en modération.',
            'Excessive caps' => 'L\'usage excessif de majuscules peut être perçu comme agressif. Message signalé.',
            'Spam keywords' => 'Votre message contient des termes suspects. Il sera examiné par nos modérateurs.',
            'Suspicious number sequence' => 'Les longues séquences de chiffres sont surveillées. Message en cours de vérification.',
            'Excessive special characters' => 'Trop de caractères spéciaux détectés. Votre message sera modéré.',
            'Repeated phrases' => 'Les phrases répétées peuvent indiquer du spam. Message signalé.'
        ];

        $friendlyReason = $messages[$reason] ?? 'Votre message présente des caractéristiques inhabituelles et sera vérifié.';

        return $friendlyReason . ' Nous apprécions votre participation, mais nous devons maintenir la qualité de nos discussions.';
    }

    /**
     * Sanitize message content
     *
     * @param string $message
     * @return string
     */
    public function sanitizeMessage(string $message): string
    {
        // Remove HTML tags
        $message = strip_tags($message);
        
        // Remove extra whitespace
        $message = preg_replace('/\s+/', ' ', $message);
        
        // Trim
        $message = trim($message);
        
        return $message;
    }

    /**
     * Check if message contains leetspeak or coded language
     *
     * @param string $message
     * @return bool
     */
    private function containsLeetspeak(string $message): bool
    {
        $leetspeakPatterns = [
            '/[4@]/',  // A
            '/[3€]/',  // E
            '/[1!|]/', // I/L
            '/[0]/',   // O
            '/[5$]/',  // S
            '/[7]/',   // T
            '/[9]/',   // G
        ];

        $leetCount = 0;
        foreach ($leetspeakPatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                $leetCount++;
            }
        }

        // If more than 2 different leetspeak characters, likely an attempt to bypass
        return $leetCount > 2;
    }

    /**
     * Check if message is a duplicate of recent messages
     *
     * @param string $message
     * @param array $recentMessages Array of recent message strings from the same user
     * @return bool
     */
    public function isDuplicateMessage(string $message, array $recentMessages = []): bool
    {
        $normalizedMessage = strtolower(trim($message));
        
        foreach ($recentMessages as $recent) {
            $normalizedRecent = strtolower(trim($recent));
            
            // Exact match
            if ($normalizedMessage === $normalizedRecent) {
                return true;
            }
            
            // Similar match (90% similarity)
            similar_text($normalizedMessage, $normalizedRecent, $percent);
            if ($percent > 90) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Check if user is posting too frequently (rate limiting)
     *
     * @param int $messageCount Number of messages in the last minute
     * @param int $timeWindowSeconds Time window in seconds
     * @return bool
     */
    public function isRateLimited(int $messageCount, int $timeWindowSeconds = 60): bool
    {
        // Allow max 5 messages per minute for normal users
        $maxMessages = $timeWindowSeconds <= 60 ? 5 : 10;
        
        return $messageCount > $maxMessages;
    }

    /**
     * Moderate message using AI (with fallback to basic checks)
     *
     * @param string $message
     * @return array ['status' => string, 'reason' => string|null]
     */
    private function moderateWithAI(string $message): array
    {
        // Check if AI moderation service is available
        if (!$this->aiModerationService || !$this->aiModerationService->isEnabled()) {
            // If AI is not available, do basic content analysis
            return $this->basicContentAnalysis($message);
        }

        try {
            // Use the dedicated AI moderation service
            $aiResult = $this->aiModerationService->moderate($message);

            return [
                'status' => $aiResult['status'],
                'reason' => $aiResult['reason'] ?? null,
                'confidence' => $aiResult['confidence'] ?? 0.5
            ];
        } catch (\Exception $e) {
            // Log the error but don't fail - fallback to basic analysis
            \Log::warning('AI moderation service failed, falling back to basic analysis', [
                'error' => $e->getMessage(),
                'message' => substr($message, 0, 100) . '...'
            ]);

            return $this->basicContentAnalysis($message);
        }
    }

    /**
     * Basic content analysis when AI is not available
     *
     * @param string $message
     * @return array
     */
    private function basicContentAnalysis(string $message): array
    {
        $lowerMessage = strtolower($message);
        
        // Check for potentially sensitive topics even without AI
        $sensitiveIndicators = [
            'kill', 'die', 'harm', 'hurt', 'abuse', 'drugs', 'suicide',
            'tuer', 'mourir', 'mal', 'blesser', 'abus', 'drogues', 'suicide'
        ];
        
        foreach ($sensitiveIndicators as $indicator) {
            if (strpos($lowerMessage, $indicator) !== false) {
                return [
                    'status' => 'flagged',
                    'reason' => 'Votre message contient des termes sensibles. Il sera signalé pour modération.'
                ];
            }
        }
        
        // Message appears safe
        return [
            'status' => 'approved',
            'reason' => null
        ];
    }
}
