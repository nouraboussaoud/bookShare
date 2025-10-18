<?php

namespace App\Http\Controllers;

use App\Models\ReadingProgress;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReadingProgressController extends Controller
{
    /**
     * Affiche toutes les progressions de lecture de l'utilisateur connecté
     */
    public function index()
    {
        $user = Auth::user();
        
        $readingProgress = ReadingProgress::where('user_id', $user->id)
            ->with('book.category')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Grouper par statut
        $grouped = [
            'reading' => $readingProgress->where('status', 'reading'),
            'to_read' => $readingProgress->where('status', 'to_read'),
            'completed' => $readingProgress->where('status', 'completed'),
            'abandoned' => $readingProgress->where('status', 'abandoned'),
        ];

        return view('reading-progress.index', compact('grouped', 'readingProgress'));
    }

    /**
     * Affiche les détails d'une progression
     */
    public function show(ReadingProgress $readingProgress)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($readingProgress->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $readingProgress->load('book.category');

        return view('reading-progress.show', compact('readingProgress'));
    }

    /**
     * Crée ou récupère une progression pour un livre
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'status' => 'nullable|in:to_read,reading,completed,abandoned',
            'current_page' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        // Vérifier si une progression existe déjà
        $progress = ReadingProgress::where('user_id', $user->id)
            ->where('book_id', $validated['book_id'])
            ->first();

        if ($progress) {
            return redirect()->route('reading-progress.show', $progress)
                ->with('info', 'Vous suivez déjà la progression de ce livre.');
        }

        // Créer une nouvelle progression
        $progress = ReadingProgress::create([
            'user_id' => $user->id,
            'book_id' => $validated['book_id'],
            'status' => $validated['status'] ?? 'to_read',
            'current_page' => $validated['current_page'] ?? 0,
            'total_pages' => $validated['total_pages'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('reading-progress.show', $progress)
            ->with('success', 'Progression de lecture ajoutée avec succès !');
    }

    /**
     * Met à jour une progression
     */
    public function update(Request $request, ReadingProgress $readingProgress)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($readingProgress->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'current_page' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:1',
            'status' => 'nullable|in:to_read,reading,completed,abandoned',
            'notes' => 'nullable|string|max:1000',
            'reading_time_minutes' => 'nullable|integer|min:0',
        ]);

        // Mettre à jour les champs
        if (isset($validated['current_page'])) {
            $readingProgress->updateProgress($validated['current_page']);
        }

        if (isset($validated['total_pages'])) {
            $readingProgress->total_pages = $validated['total_pages'];
        }

        if (isset($validated['status'])) {
            $readingProgress->status = $validated['status'];
            
            // Gérer les timestamps selon le statut
            if ($validated['status'] === 'reading' && !$readingProgress->started_at) {
                $readingProgress->startReading();
            } elseif ($validated['status'] === 'completed') {
                $readingProgress->completeReading();
            } elseif ($validated['status'] === 'abandoned') {
                $readingProgress->abandonReading();
            }
        }

        if (isset($validated['notes'])) {
            $readingProgress->notes = $validated['notes'];
        }

        if (isset($validated['reading_time_minutes'])) {
            $readingProgress->reading_time_minutes = $validated['reading_time_minutes'];
        }

        $readingProgress->save();

        return redirect()->back()
            ->with('success', 'Progression mise à jour avec succès !');
    }

    /**
     * Supprime une progression
     */
    public function destroy(ReadingProgress $readingProgress)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($readingProgress->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $readingProgress->delete();

        return redirect()->route('reading-progress.index')
            ->with('success', 'Progression supprimée avec succès !');
    }

    /**
     * Ajoute du temps de lecture
     */
    public function addReadingTime(Request $request, ReadingProgress $readingProgress)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($readingProgress->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $validated = $request->validate([
            'minutes' => 'required|integer|min:1|max:1440', // Max 24h
        ]);

        $readingProgress->addReadingTime($validated['minutes']);

        return redirect()->back()
            ->with('success', 'Temps de lecture ajouté !');
    }

    /**
     * Marque un livre comme "à lire"
     */
    public function markAsToRead(Book $book)
    {
        $user = Auth::user();

        $progress = ReadingProgress::firstOrCreate(
            [
                'user_id' => $user->id,
                'book_id' => $book->id,
            ],
            [
                'status' => 'to_read',
                'current_page' => 0,
            ]
        );

        return redirect()->back()
            ->with('success', 'Livre ajouté à votre liste "À lire" !');
    }

    /**
     * Démarre la lecture d'un livre
     */
    public function startReading(Book $book)
    {
        $user = Auth::user();

        $progress = ReadingProgress::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->first();

        if (!$progress) {
            $progress = ReadingProgress::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'status' => 'reading',
                'current_page' => 0,
                'started_at' => now(),
            ]);
        } else {
            $progress->startReading();
        }

        return redirect()->back()
            ->with('success', 'Bonne lecture !');
    }

    /**
     * Marque un livre comme terminé
     */
    public function markAsCompleted(ReadingProgress $readingProgress)
    {
        // Vérifier que l'utilisateur est propriétaire
        if ($readingProgress->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $readingProgress->completeReading();

        return redirect()->back()
            ->with('success', 'Félicitations ! Livre marqué comme terminé.');
    }

    /**
     * Statistiques de lecture de l'utilisateur
     */
    public function statistics()
    {
        $user = Auth::user();

        $stats = [
            'total_books' => ReadingProgress::where('user_id', $user->id)->count(),
            'reading' => ReadingProgress::where('user_id', $user->id)->reading()->count(),
            'completed' => ReadingProgress::where('user_id', $user->id)->completed()->count(),
            'to_read' => ReadingProgress::where('user_id', $user->id)->toRead()->count(),
            'abandoned' => ReadingProgress::where('user_id', $user->id)->abandoned()->count(),
            'total_pages_read' => ReadingProgress::where('user_id', $user->id)
                ->completed()
                ->sum('total_pages'),
            'total_reading_time' => ReadingProgress::where('user_id', $user->id)
                ->sum('reading_time_minutes'),
        ];

        // Livres récemment terminés
        $recentlyCompleted = ReadingProgress::where('user_id', $user->id)
            ->completed()
            ->with('book')
            ->orderBy('finished_at', 'desc')
            ->limit(5)
            ->get();

        return view('reading-progress.statistics', compact('stats', 'recentlyCompleted'));
    }
}
