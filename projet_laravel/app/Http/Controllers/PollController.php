<?php

namespace App\Http\Controllers;

use App\Models\GroupEvent;
use App\Models\Poll;
use App\Models\PollOption;
use App\Models\PollVote;
use App\Models\User;
use App\Models\ReadingGroup;
use App\Services\NotificationService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PollController extends Controller
{
    use AuthorizesRequests;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Check if user can view a reading group
     */
    private function canUserViewGroup(User $user, ReadingGroup $readingGroup): bool
    {
        // Public groups can be viewed by anyone
        if (!$readingGroup->is_private) {
            return true;
        }

        // Owner can always view
        if ($user->id === $readingGroup->owner_id) {
            return true;
        }

        // Check if user is an approved/active member
        return $readingGroup->members()
                    ->where('user_id', $user->id)
                    ->whereIn('status', ['approved', 'active', 'accepted'])
                    ->exists();
    }

    /**
     * Show create poll form for an event
     */
    public function create($readingGroup, GroupEvent $event)
    {
        // Only event organizers can create polls
        $this->authorize('manageMembership', $event->readingGroup);

        return view('polls.create', compact('event'));
    }

    /**
     * Store a new poll
     */
    public function store(Request $request, $readingGroup, GroupEvent $event)
    {
        $this->authorize('manageMembership', $event->readingGroup);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:yes_no,multiple_choice,rating',
            'options' => 'required_if:type,multiple_choice|array|min:2|max:10',
            'options.*' => 'string|max:255',
            'closes_at' => 'nullable|date_format:Y-m-d\TH:i|after:now',
        ]);

        // Default close time to event end time if not specified
        $closesAt = $validated['closes_at'] ?? $event->getEventEndTime();

        $poll = Poll::create([
            'event_id' => $event->id,
            'created_by' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'closes_at' => $closesAt,
            'is_active' => true,
        ]);

        // Create poll options for multiple choice and yes/no
        if ($validated['type'] === 'multiple_choice') {
            $options = $validated['options'] ?? [];
            foreach ($options as $index => $optionText) {
                PollOption::create([
                    'poll_id' => $poll->id,
                    'text' => $optionText,
                    'order' => $index,
                ]);
            }
        } elseif ($validated['type'] === 'yes_no') {
            PollOption::create(['poll_id' => $poll->id, 'text' => 'Oui', 'order' => 0]);
            PollOption::create(['poll_id' => $poll->id, 'text' => 'Non', 'order' => 1]);
        }

        // Send poll started notifications
        $this->notificationService->notifyPollStarted($poll);

        return redirect()
            ->route('reading-groups.events.show', [$event->readingGroup, $event])
            ->with('status', 'Sondage créé avec succès!');
    }

    /**
     * Show poll results and details
     */
    public function show($readingGroup, GroupEvent $event, Poll $poll)
    {
        // Verify poll belongs to this event
        if ($poll->event_id !== $event->id) {
            abort(404);
        }

        // Check if user can view the reading group
        if (Auth::check()) {
            $canView = $this->canUserViewGroup(Auth::user(), $event->readingGroup);
            abort_unless($canView, 403);
        } else {
            // If not authenticated but group is public, still allow
            abort_unless(!$event->readingGroup->is_private, 403);
        }

        $results = $poll->getResults();
        $userVote = Auth::check() ? $poll->getUserVote(Auth::id()) : null;
        $userHasVoted = Auth::check() ? $poll->userHasVoted(Auth::id()) : false;

        return view('polls.show', compact('poll', 'event', 'results', 'userVote', 'userHasVoted'));
    }

    /**
     * Vote on a poll
     */
    public function vote(Request $request, $readingGroup, GroupEvent $event, Poll $poll)
    {
        // Verify poll belongs to this event
        if ($poll->event_id !== $event->id) {
            abort(404);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json(['error' => 'Vous devez être connecté pour voter'], 401);
        }

        $user = Auth::user();
        
        // Check if user can view the reading group (public or member)
        $canView = $this->canUserViewGroup($user, $event->readingGroup);
        if (!$canView) {
            return response()->json(['error' => 'Vous n\'avez pas accès à ce groupe'], 403);
        }

        // Check if poll is active
        if (!$poll->isActive()) {
            return response()->json(['error' => 'Ce sondage est fermé'], 403);
        }

        $validated = $request->validate([
            'poll_option_id' => 'nullable|exists:poll_options,id',
            'rating_value' => 'nullable|integer|min:1|max:5',
        ]);

        // For non-rating polls, ensure option_id is provided
        if ($poll->type !== 'rating' && !$validated['poll_option_id']) {
            return response()->json(['error' => 'Vous devez sélectionner une option'], 422);
        }

        // For rating polls, ensure rating_value is provided
        if ($poll->type === 'rating' && !$validated['rating_value']) {
            return response()->json(['error' => 'Vous devez donner une note'], 422);
        }

        // Check if user already voted
        $existingVote = $poll->getUserVote(Auth::id());
        
        if ($existingVote) {
            // Update existing vote
            $existingVote->update([
                'poll_option_id' => $validated['poll_option_id'] ?? null,
                'rating_value' => $validated['rating_value'] ?? null,
            ]);
            $vote = $existingVote;
            $message = 'Votre vote a été mis à jour';
        } else {
            // Create new vote
            $vote = PollVote::create([
                'poll_id' => $poll->id,
                'poll_option_id' => $validated['poll_option_id'] ?? null,
                'user_id' => Auth::id(),
                'rating_value' => $validated['rating_value'] ?? null,
            ]);
            $message = 'Votre vote a été enregistré';
        }

        // Return updated results
        $results = $poll->getResults();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'results' => $results,
            ]);
        }

        return redirect()
            ->route('polls.show', [$event->readingGroup, $event, $poll])
            ->with('status', $message);
    }

    /**
     * Get live poll results (for AJAX)
     */
    public function getResults($readingGroup, GroupEvent $event, Poll $poll)
    {
        // Verify poll belongs to this event
        if ($poll->event_id !== $event->id) {
            abort(404);
        }

        // Allow if authenticated and can view reading group
        if (Auth::check()) {
            $canView = $this->canUserViewGroup(Auth::user(), $event->readingGroup);
            abort_unless($canView, 403);
        } else {
            abort_unless(!$event->readingGroup->is_private, 403);
        }

        $results = $poll->getResults();
        $userVote = Auth::check() ? $poll->getUserVote(Auth::id()) : null;
        $userHasVoted = Auth::check() ? $poll->userHasVoted(Auth::id()) : false;

        return response()->json([
            'results' => $results,
            'user_voted' => $userHasVoted,
            'user_vote' => $userVote,
            'is_active' => $poll->isActive(),
        ]);
    }

    /**
     * Close a poll early
     */
    public function close($readingGroup, GroupEvent $event, Poll $poll)
    {
        // Verify poll belongs to this event
        if ($poll->event_id !== $event->id) {
            abort(404);
        }

        // Only event organizers can close polls
        $this->authorize('manageMembership', $event->readingGroup);

        $poll->close();

        return redirect()
            ->route('reading-groups.events.show', [$event->readingGroup, $event])
            ->with('status', 'Sondage fermé avec succès');
    }

    /**
     * Export poll results as CSV
     */
    public function exportResults($readingGroup, GroupEvent $event, Poll $poll)
    {
        // Verify poll belongs to this event
        if ($poll->event_id !== $event->id) {
            abort(404);
        }

        // Only poll creator can export
        abort_unless(Auth::check() && Auth::id() === $poll->created_by, 403);

        $results = $poll->getResults();
        $fileName = 'poll-results-' . $poll->id . '-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($poll, $results) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['Sondage', 'Type', 'Titre']);
            fputcsv($file, [$poll->id, $poll->type, $poll->title]);
            fputcsv($file, []); // Empty line

            // Results section
            if ($poll->type === 'rating') {
                fputcsv($file, ['Note', 'Nombre de votes', 'Pourcentage']);
                $totalVotes = $results['total_votes'];
                for ($i = 1; $i <= 5; $i++) {
                    $votes = $results['data'][$i] ?? 0;
                    $percentage = $totalVotes > 0 ? round(($votes / $totalVotes) * 100, 2) : 0;
                    fputcsv($file, [$i, $votes, $percentage . '%']);
                }
                fputcsv($file, []);
                fputcsv($file, ['Note moyenne', $results['average'] ?? 0]);
            } else {
                fputcsv($file, ['Option', 'Nombre de votes', 'Pourcentage']);
                foreach ($results['data'] as $option) {
                    fputcsv($file, [
                        $option['text'],
                        $option['votes'],
                        $option['percentage'] . '%',
                    ]);
                }
            }

            fputcsv($file, []);
            fputcsv($file, ['Total des votes', $results['total_votes']]);
            fputcsv($file, ['Date d\'export', now()->format('Y-m-d H:i:s')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Delete a poll
     */
    public function destroy(GroupEvent $event, Poll $poll)
    {
        // Verify poll belongs to this event
        if ($poll->event_id !== $event->id) {
            abort(404);
        }

        // Only poll creator or event organizers can delete
        $this->authorize('manageMembership', $event->readingGroup);

        $poll->delete();

        return redirect()
            ->route('reading-groups.events.show', [$event->readingGroup, $event])
            ->with('status', 'Sondage supprimé avec succès');
    }
}
