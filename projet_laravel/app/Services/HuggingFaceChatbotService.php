<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HuggingFaceChatbotService
{
    // Using a free model that works with Hugging Face Inference API
    protected $apiUrl = 'https://api-inference.huggingface.co/models/microsoft/DialoGPT-large';
    protected $apiToken;

    public function __construct()
    {
        $this->apiToken = config('services.huggingface.token');
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

        // Build prompt
        $prompt = $this->buildPrompt($userMessage, $conversationHistory, $booksContext);

        try {
            // Build a simple conversational prompt
            $conversationText = $this->buildConversationText($conversationHistory, $userMessage);
            
            // Call Hugging Face API with DialoGPT format
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($this->apiUrl, [
                'inputs' => [
                    'past_user_inputs' => $this->getPastUserInputs($conversationHistory),
                    'generated_responses' => $this->getPastResponses($conversationHistory),
                    'text' => $userMessage,
                ],
                'parameters' => [
                    'max_length' => 100,
                    'temperature' => 0.9,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract the generated text from DialoGPT response
                $assistantMessage = $data['generated_text'] ?? $data[0]['generated_text'] ?? null;
                
                // If no response, provide a contextual one based on books
                if (!$assistantMessage) {
                    $assistantMessage = $this->generateContextualResponse($userMessage, $booksContext);
                } else {
                    // Clean up the response
                    $assistantMessage = $this->cleanResponse($assistantMessage);
                }

                // Save assistant response
                $this->saveMessage($sessionId, 'assistant', $assistantMessage);

                return [
                    'success' => true,
                    'message' => $assistantMessage,
                    'timestamp' => now()->toIso8601String(),
                ];
            } else {
                Log::error('Hugging Face API error: ' . $response->body());
                
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
     * Build prompt for Hugging Face
     */
    private function buildPrompt(string $userMessage, $conversationHistory, string $booksContext): string
    {
        $systemPrompt = "Tu es un assistant virtuel intelligent pour BookShare, une plateforme de partage de livres.

Ton rôle:
- Aider les utilisateurs à trouver des livres qui correspondent à leurs goûts
- Répondre aux questions sur les livres disponibles
- Donner des recommandations personnalisées
- Expliquer comment utiliser la plateforme
- Être amical, serviable et enthousiaste

Règles importantes:
- Réponds TOUJOURS en français
- Sois concis (maximum 3-4 phrases)
- Si tu recommandes un livre, mentionne son titre et auteur
- Si tu ne connais pas la réponse, dis-le honnêtement
- Encourage la lecture et le partage de livres

Contexte des livres disponibles:
{$booksContext}

";

        // Build conversation context
        $conversationText = "";
        foreach ($conversationHistory as $msg) {
            $role = $msg->role === 'user' ? 'Utilisateur' : 'Assistant';
            $conversationText .= "{$role}: {$msg->message}\n";
        }

        // Combine everything
        $fullPrompt = "[INST] {$systemPrompt}";
        
        if (!empty($conversationText)) {
            $fullPrompt .= "\nHistorique de conversation:\n{$conversationText}";
        }
        
        $fullPrompt .= "\nUtilisateur: {$userMessage}\n\nRéponds en français de manière concise et amicale: [/INST]";

        return $fullPrompt;
    }

    /**
     * Get past user inputs for DialoGPT
     */
    private function getPastUserInputs($conversationHistory): array
    {
        return $conversationHistory
            ->where('role', 'user')
            ->pluck('message')
            ->take(3)
            ->values()
            ->toArray();
    }

    /**
     * Get past responses for DialoGPT
     */
    private function getPastResponses($conversationHistory): array
    {
        return $conversationHistory
            ->where('role', 'assistant')
            ->pluck('message')
            ->take(3)
            ->values()
            ->toArray();
    }

    /**
     * Build conversation text
     */
    private function buildConversationText($conversationHistory, string $userMessage): string
    {
        $text = "";
        foreach ($conversationHistory->take(4) as $msg) {
            $text .= ($msg->role === 'user' ? 'User: ' : 'Bot: ') . $msg->message . "\n";
        }
        $text .= "User: {$userMessage}\nBot:";
        return $text;
    }

    /**
     * Generate contextual response based on books
     */
    private function generateContextualResponse(string $userMessage, string $booksContext): string
    {
        $lowerMessage = strtolower($userMessage);
        
        // Detect question type and provide relevant response
        if (strpos($lowerMessage, 'science-fiction') !== false || strpos($lowerMessage, 'science fiction') !== false || strpos($lowerMessage, 'sf') !== false) {
            $sciFiBooks = Book::whereHas('category', function($q) {
                $q->where('name', 'LIKE', '%science%')->orWhere('name', 'LIKE', '%fiction%');
            })->take(3)->get();
            
            if ($sciFiBooks->count() > 0) {
                $response = "Voici nos livres de science-fiction disponibles :\n";
                foreach ($sciFiBooks as $book) {
                    $response .= "📚 {$book->titre} par {$book->auteur}\n";
                }
                return trim($response);
            }
        }
        
        if (strpos($lowerMessage, 'recommande') !== false || strpos($lowerMessage, 'suggère') !== false) {
            $randomBooks = Book::inRandomOrder()->take(2)->get();
            if ($randomBooks->count() > 0) {
                $response = "Je vous recommande :\n";
                foreach ($randomBooks as $book) {
                    $response .= "📖 {$book->titre} par {$book->auteur}\n";
                }
                return trim($response);
            }
        }
        
        if (strpos($lowerMessage, 'bonjour') !== false || strpos($lowerMessage, 'salut') !== false) {
            return "Bonjour ! 👋 Je suis votre assistant BookShare. Comment puis-je vous aider à trouver un livre aujourd'hui ?";
        }
        
        if (strpos($lowerMessage, 'emprunter') !== false) {
            return "Pour emprunter un livre, consultez la page des livres disponibles et cliquez sur le livre qui vous intéresse. Vous y trouverez les détails et les options d'emprunt.";
        }
        
        // Default response
        return "Je suis là pour vous aider à trouver des livres ! Vous pouvez me demander des recommandations, chercher par catégorie, ou poser des questions sur la plateforme.";
    }

    /**
     * Clean up the AI response
     */
    private function cleanResponse(string $response): string
    {
        // Remove extra whitespace
        $response = trim($response);
        
        // Remove any instruction tags that might leak through
        $response = preg_replace('/\[INST\].*?\[\/INST\]/s', '', $response);
        $response = preg_replace('/\[\/INST\]/', '', $response);
        
        // Remove "Assistant:" prefix if present
        $response = preg_replace('/^(Assistant|Utilisateur|User:|Bot:):\s*/i', '', $response);
        
        // Limit length
        if (strlen($response) > 500) {
            $response = substr($response, 0, 497) . '...';
        }
        
        return trim($response);
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
    private function getConversationHistory(string $sessionId, int $limit = 6): \Illuminate\Support\Collection
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
