<?php

namespace App\Http\Controllers;

use App\Services\GroqChatbotService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(GroqChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }

    /**
     * Send a message to the chatbot
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string',
        ]);

        $sessionId = $request->session_id ?? Str::uuid()->toString();
        $userMessage = $request->message;

        $response = $this->chatbotService->generateResponse($userMessage, $sessionId);

        return response()->json([
            'success' => $response['success'],
            'message' => $response['message'],
            'session_id' => $sessionId,
            'timestamp' => $response['timestamp'] ?? now()->toIso8601String(),
        ]);
    }

    /**
     * Get chat history
     */
    public function getHistory(Request $request)
    {
        $sessionId = $request->session_id;

        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'message' => 'Session ID required',
            ], 400);
        }

        $history = $this->chatbotService->getChatHistory($sessionId);

        return response()->json([
            'success' => true,
            'history' => $history,
        ]);
    }

    /**
     * Clear chat history
     */
    public function clearHistory(Request $request)
    {
        $sessionId = $request->session_id;

        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'message' => 'Session ID required',
            ], 400);
        }

        $cleared = $this->chatbotService->clearChatHistory($sessionId);

        return response()->json([
            'success' => $cleared,
            'message' => $cleared ? 'Historique effacé' : 'Aucun historique à effacer',
        ]);
    }

    /**
     * Get suggested questions
     */
    public function getSuggestions()
    {
        $suggestions = $this->chatbotService->getSuggestedQuestions();

        return response()->json([
            'success' => true,
            'suggestions' => $suggestions,
        ]);
    }
}
