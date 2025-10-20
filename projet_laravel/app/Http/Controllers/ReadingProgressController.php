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

        // Calculer le total de pages lues : 
        // - Pour les livres terminés : total_pages
        // - Pour les livres en cours : current_page
        // - Pour les livres à lire/abandonnés : 0 ou current_page si > 0
        $totalPagesRead = ReadingProgress::where('user_id', $user->id)
            ->get()
            ->sum(function ($progress) {
                if ($progress->status === 'completed') {
                    return $progress->total_pages ?? 0;
                } else {
                    return $progress->current_page ?? 0;
                }
            });

        $stats = [
            'total_books' => ReadingProgress::where('user_id', $user->id)->count(),
            'reading' => ReadingProgress::where('user_id', $user->id)->reading()->count(),
            'completed' => ReadingProgress::where('user_id', $user->id)->completed()->count(),
            'to_read' => ReadingProgress::where('user_id', $user->id)->toRead()->count(),
            'abandoned' => ReadingProgress::where('user_id', $user->id)->abandoned()->count(),
            'total_pages_read' => $totalPagesRead,
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

        // Évaluation de l'utilisateur
        $evaluation = $this->evaluateUser($stats, $totalPagesRead);

        return view('reading-progress.statistics', compact('stats', 'recentlyCompleted', 'evaluation'));
    }

    /**
     * Évaluer l'utilisateur selon ses statistiques de lecture
     */
    private function evaluateUser($stats, $totalPagesRead)
    {
        $score = 0;
        $badges = [];
        $recommendations = [];

        // Critère 1: Nombre de livres terminés (max 30 points)
        $completedBooks = $stats['completed'];
        if ($completedBooks >= 20) {
            $score += 30;
            $badges[] = ['name' => 'Grand Lecteur', 'icon' => '📚', 'color' => 'gold'];
        } elseif ($completedBooks >= 10) {
            $score += 20;
            $badges[] = ['name' => 'Lecteur Assidu', 'icon' => '📖', 'color' => 'silver'];
        } elseif ($completedBooks >= 5) {
            $score += 10;
            $badges[] = ['name' => 'Lecteur Régulier', 'icon' => '📕', 'color' => 'bronze'];
        } else {
            $recommendations[] = 'Terminez plus de livres pour progresser !';
        }

        // Critère 2: Nombre total de pages lues (max 25 points)
        if ($totalPagesRead >= 5000) {
            $score += 25;
            $badges[] = ['name' => 'Dévoreur de Pages', 'icon' => '🔥', 'color' => 'danger'];
        } elseif ($totalPagesRead >= 2000) {
            $score += 20;
        } elseif ($totalPagesRead >= 1000) {
            $score += 15;
        } elseif ($totalPagesRead >= 500) {
            $score += 10;
        } else {
            $recommendations[] = 'Lisez plus de pages pour améliorer votre score !';
        }

        // Critère 3: Temps de lecture (max 20 points)
        $readingTimeHours = $stats['total_reading_time'] / 60;
        if ($readingTimeHours >= 100) {
            $score += 20;
            $badges[] = ['name' => 'Marathon de Lecture', 'icon' => '⏰', 'color' => 'info'];
        } elseif ($readingTimeHours >= 50) {
            $score += 15;
        } elseif ($readingTimeHours >= 20) {
            $score += 10;
        } elseif ($readingTimeHours >= 10) {
            $score += 5;
        } else {
            $recommendations[] = 'Passez plus de temps à lire !';
        }

        // Critère 4: Taux de complétion (max 15 points)
        $completionRate = $stats['total_books'] > 0 
            ? ($stats['completed'] / $stats['total_books']) * 100 
            : 0;
        if ($completionRate >= 80) {
            $score += 15;
            $badges[] = ['name' => 'Finisseur', 'icon' => '✅', 'color' => 'success'];
        } elseif ($completionRate >= 60) {
            $score += 10;
        } elseif ($completionRate >= 40) {
            $score += 5;
        } else {
            $recommendations[] = 'Terminez plus de livres que vous commencez !';
        }

        // Critère 5: Diversité (livres en cours) (max 10 points)
        $readingBooks = $stats['reading'];
        if ($readingBooks >= 3 && $readingBooks <= 7) {
            $score += 10;
            $badges[] = ['name' => 'Lecteur Organisé', 'icon' => '🎯', 'color' => 'primary'];
        } elseif ($readingBooks >= 1 && $readingBooks <= 10) {
            $score += 5;
        } else {
            $recommendations[] = 'Maintenez entre 3 et 7 livres en cours pour une lecture optimale !';
        }

        // Déterminer le niveau selon le score
        if ($score >= 85) {
            $level = [
                'name' => 'Maître Lecteur',
                'icon' => '👑',
                'color' => 'warning',
                'rank' => 'S',
                'description' => 'Vous êtes un véritable passionné de lecture ! Continue comme ça !'
            ];
        } elseif ($score >= 70) {
            $level = [
                'name' => 'Expert',
                'icon' => '🌟',
                'color' => 'info',
                'rank' => 'A',
                'description' => 'Excellentes habitudes de lecture ! Vous êtes sur la bonne voie.'
            ];
        } elseif ($score >= 50) {
            $level = [
                'name' => 'Intermédiaire',
                'icon' => '📘',
                'color' => 'primary',
                'rank' => 'B',
                'description' => 'Bon lecteur ! Quelques améliorations vous mèneront au niveau supérieur.'
            ];
        } elseif ($score >= 30) {
            $level = [
                'name' => 'Débutant',
                'icon' => '🌱',
                'color' => 'success',
                'rank' => 'C',
                'description' => 'Bon début ! Continuez à lire régulièrement pour progresser.'
            ];
        } else {
            $level = [
                'name' => 'Novice',
                'icon' => '🐣',
                'color' => 'secondary',
                'rank' => 'D',
                'description' => 'Bienvenue ! Commencez votre aventure de lecture dès maintenant !'
            ];
        }

        return [
            'score' => $score,
            'max_score' => 100,
            'level' => $level,
            'badges' => $badges,
            'recommendations' => $recommendations,
            'completion_rate' => round($completionRate, 1),
            'reading_time_hours' => round($readingTimeHours, 1),
        ];
    }
}
