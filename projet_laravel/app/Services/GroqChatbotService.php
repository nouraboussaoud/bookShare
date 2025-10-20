<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqChatbotService
{
    protected $apiUrl = 'https://api.groq.com/openai/v1/chat/completions';
    protected $apiKey;
    protected $model = 'llama-3.1-8b-instant'; // Latest fast model

    public function __construct()
    {
        $this->apiKey = config('services.groq.api_key');
    }

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

        // Build messages for the API
        $messages = $this->buildMessages($userMessage, $conversationHistory, $booksContext);

        try {
            // Call Groq API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 500,
                'top_p' => 1,
                'stream' => false,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract the assistant's message
                $assistantMessage = $data['choices'][0]['message']['content'] ?? 'Désolé, je n\'ai pas pu générer une réponse.';
                
                // Clean up the response
                $assistantMessage = trim($assistantMessage);

                // Save assistant response
                $this->saveMessage($sessionId, 'assistant', $assistantMessage);

                return [
                    'success' => true,
                    'message' => $assistantMessage,
                    'timestamp' => now()->toIso8601String(),
                ];
            } else {
                Log::error('Groq API error: ' . $response->body());
                
                return [
                    'success' => false,
                    'message' => "Désolé, je rencontre un problème technique. Veuillez réessayer dans un moment.",
                    'error' => $response->body(),
                ];
            }

        } catch (\Exception $e) {
            Log::error('Chatbot error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => "Désolé, je rencontre un problème technique. Veuillez réessayer dans un moment.",
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build messages array for Groq API
     */
    private function buildMessages(string $userMessage, $conversationHistory, string $booksContext): array
    {
        $messages = [];

        // System message with context
        $systemPrompt = "Tu es un assistant virtuel intelligent pour BookShare, une plateforme de partage de livres.

Ton rôle:
- Aider les utilisateurs à trouver des livres qui correspondent à leurs goûts
- Répondre aux questions sur les livres disponibles
- Donner des recommandations personnalisées basées sur les livres disponibles
- Expliquer comment utiliser la plateforme
- Être amical, serviable et enthousiaste

Règles importantes:
- Réponds TOUJOURS en français
- Sois concis (maximum 3-4 phrases)
- Si tu recommandes un livre, mentionne son titre et auteur
- Si tu ne connais pas la réponse, dis-le honnêtement
- Encourage la lecture et le partage de livres
- Utilise des emojis pour rendre tes réponses plus amicales (📚 📖 ✨ 👋)

{$booksContext}";

        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt,
        ];

        // Add conversation history (last 6 messages)
        foreach ($conversationHistory->take(6) as $msg) {
            $messages[] = [
                'role' => $msg->role === 'user' ? 'user' : 'assistant',
                'content' => $msg->message,
            ];
        }

        // Add current user message
        $messages[] = [
            'role' => 'user',
            'content' => $userMessage,
        ];

        return $messages;
    }

    /**
     * Get books context for AI
     */
    private function getBooksContext(): string
    {
        $categories = Category::with('books')->get();
        $context = "Voici les livres disponibles dans la bibliothèque BookShare:\n\n";

        foreach ($categories as $category) {
            if ($category->books->count() > 0) {
                $context .= "📚 Catégorie {$category->name}:\n";
                foreach ($category->books->take(5) as $book) {
                    $context .= "  - \"{$book->titre}\" par {$book->auteur}";
                    if ($book->description) {
                        $context .= " - " . substr($book->description, 0, 100);
                    }
                    $context .= "\n";
                }
                $context .= "\n";
            }
        }

        // Add recent books
        $recentBooks = Book::orderBy('created_at', 'desc')->take(5)->get();
        if ($recentBooks->count() > 0) {
            $context .= "🔥 Livres récemment ajoutés:\n";
            foreach ($recentBooks as $book) {
                $context .= "  - \"{$book->titre}\" par {$book->auteur}\n";
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
