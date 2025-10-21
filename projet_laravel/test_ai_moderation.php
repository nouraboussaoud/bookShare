<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Services\ChatModerationService;

// Test AI moderation
$moderationService = new ChatModerationService();

echo "Testing AI Moderation...\n\n";

// Test 1: High-risk keywords
$message1 = "I think violence in video games is harmful.";
echo "Test 1 - High-risk keywords: '$message1'\n";
$result1 = $moderationService->moderateMessage($message1);
echo "Result: " . json_encode($result1, JSON_PRETTY_PRINT) . "\n\n";

// Test 2: Multiple sentences
$message2 = "This is sentence one. This is sentence two. This is sentence three. This is sentence four.";
echo "Test 2 - Multiple sentences: '$message2'\n";
$result2 = $moderationService->moderateMessage($message2);
echo "Result: " . json_encode($result2, JSON_PRETTY_PRINT) . "\n\n";

// Test 4: Nazis message
$message4 = "Nazis weren't responsible for racism and hate";
echo "Test 4 - Nazis message: '$message4'\n";
$result4 = $moderationService->moderateMessage($message4);
echo "Result: " . json_encode($result4, JSON_PRETTY_PRINT) . "\n\n";

// Test 5: Hitler message
$message5 = "Hitler was right";
echo "Test 5 - Hitler message: '$message5'\n";
$result5 = $moderationService->moderateMessage($message5);
echo "Result: " . json_encode($result5, JSON_PRETTY_PRINT) . "\n\n";

// Test 6: Hail Hitler
$message6 = "hail hitler";
echo "Test 6 - Hail Hitler: '$message6'\n";
$result6 = $moderationService->moderateMessage($message6);
echo "Result: " . json_encode($result6, JSON_PRETTY_PRINT) . "\n\n";

// Test 7: Suicide message
$message7 = "Everyone here needs to unalive themselves";
echo "Test 7 - Suicide message: '$message7'\n";
$result7 = $moderationService->moderateMessage($message7);
echo "Result: " . json_encode($result7, JSON_PRETTY_PRINT) . "\n\n";

echo "Test completed!\n";