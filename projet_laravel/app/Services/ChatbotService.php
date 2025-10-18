<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\Book;
use App\Models\Category;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Auth;

class ChatbotService
{
    /**
     * Generate a response from the AI chatbot
     */
    public function generateResponse(string $userMessage, string $sessionId): array
    {
        // Save user message
        $this->saveMessage($sessionId, 'user', $userMessage);

        // Get conversation history
        $conversationHistory = $this->getConversationHistory($sessionId);

        // Get context about books
        $booksContext = $this->getBooksContext();

        // Build system prompt
        $systemPrompt = $this->buildSystemPrompt($booksContext);

        // Prepare messages for OpenAI
        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        // Add conversation history
        foreach ($conversationHistory as $msg) {
            $messages[] = [
                'role' => $msg->role,
                'content' => $msg->message,
            ];
        }

        // Add current user message
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        try {
            // Call OpenAI API
            $response = OpenAI::chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            $assistantMessage = $response->choices[0]->message->content;

            // Save assistant response
            $this->saveMessage($sessionId, 'assistant', $assistantMessage);

            return [
                'success' => true,
                'message' => $assistantMessage,
                'timestamp' => now()->toIso8601String(),
            ];

        } catch (\Exception $e) {
            \Log::error('Chatbot error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => "Désolé, je rencontre un problème technique. Veuillez réessayer dans un moment.",
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build system prompt with context
     */
    private function buildSystemPrompt(string $booksContext): string
    {
        return "Tu es un assistant virtuel intelligent pour BookShare, une plateforme de partage de livres.

Ton rôle:
- Aider les utilisateurs à trouver des livres qui correspondent à leurs goûts
- Répondre aux questions sur les livres disponibles
- Donner des recommandations personnalisées
- Expliquer comment utiliser la plateforme
- Être amical, serviable et enthousiaste

Règles importantes:
- Réponds toujours en français
- Sois concis mais informatif (maximum 3-4 phrases)
- Si tu recommandes un livre, mentionne son titre et pourquoi il pourrait plaire
- Si tu ne connais pas la réponse, dis-le honnêtement
- Encourage la lecture et le partage de livres

Contexte des livres disponibles:
{$booksContext}

Exemples de questions que tu peux recevoir:
- \"Je cherche un livre de science-fiction\"
- \"Quels sont les livres les plus populaires?\"
- \"Comment puis-je emprunter un livre?\"
- \"Recommande-moi un bon roman\"

Sois naturel et conversationnel!";
    }

    /**
     * Get books context for AI
     */
    private function getBooksContext(): string
    {
        $categories = Category::with('books')->get();
        $context = "Catégories et livres disponibles:\n\n";

        foreach ($categories as $category) {
            if ($category->books->count() > 0) {
                $context .= "📚 {$category->name}:\n";
                foreach ($category->books->take(5) as $book) {
                    $context .= "  - {$book->titre} par {$book->auteur}\n";
                }
                $context .= "\n";
            }
        }

        // Add popular books
        $popularBooks = Book::orderBy('created_at', 'desc')->take(5)->get();
        if ($popularBooks->count() > 0) {
            $context .= "🔥 Livres récents:\n";
            foreach ($popularBooks as $book) {
                $context .= "  - {$book->titre} par {$book->auteur}\n";
            }
        }

        return $context;
    }

    /**
     * Get conversation history
     */
    private function getConversationHistory(string $sessionId, int $limit = 10): \Illuminate\Support\Collection
    {
        $query = ChatMessage::where('session_id', $sessionId);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        return $query->orderBy('created_at', 'desc')
            ->take($limit)
            ->get()
            ->reverse()
            ->values();
    }

    /**
     * Save message to database
     */
    private function saveMessage(string $sessionId, string $role, string $message): ChatMessage
    {
        return ChatMessage::create([
            'user_id' => Auth::id(),
            'session_id' => $sessionId,
            'role' => $role,
            'message' => $message,
        ]);
    }

    /**
     * Get chat history for a session
     */
    public function getChatHistory(string $sessionId): array
    {
        $messages = $this->getConversationHistory($sessionId, 50);

        return $messages->map(function ($msg) {
            return [
                'role' => $msg->role,
                'message' => $msg->message,
                'timestamp' => $msg->created_at->format('H:i'),
                'isUser' => $msg->isFromUser(),
            ];
        })->toArray();
    }

    /**
     * Clear chat history
     */
    public function clearChatHistory(string $sessionId): bool
    {
        $query = ChatMessage::where('session_id', $sessionId);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        }

        return $query->delete() > 0;
    }

    /**
     * Get suggested questions
     */
    public function getSuggestedQuestions(): array
    {
        return [
            "Recommande-moi un bon livre",
            "Quels sont les livres de science-fiction disponibles?",
            "Je cherche un roman d'aventure",
            "Comment emprunter un livre?",
            "Quels sont les livres les plus populaires?",
            "Je veux lire quelque chose de léger",
        ];
    }
}
