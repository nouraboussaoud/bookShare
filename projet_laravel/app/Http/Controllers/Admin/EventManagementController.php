<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupEvent;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventManagementController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $q = $request->input('q');
        $perPage = (int) $request->input('per_page', 20);

        $query = GroupEvent::query();
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // order by event_date then event_time (fallback to created_at)
        $events = $query->orderByDesc('event_date')->orderByDesc('event_time')->paginate($perPage)->withQueryString();

        // KPIs
        $totalEvents = GroupEvent::count();
        $upcoming = GroupEvent::where('event_date', '>=', now()->toDateString())->count();
        $totalParticipants = $this->getTotalEventParticipants();
        $totalMessages = $this->getTotalEventMessages();

        return view('admin.evenements.index', compact('events', 'totalEvents', 'upcoming', 'totalParticipants', 'totalMessages', 'q', 'perPage'));
    }

    public function show(GroupEvent $groupEvent)
    {
        return view('admin.evenements.show', ['event' => $groupEvent]);
    }

    public function edit(GroupEvent $groupEvent)
    {
        return view('admin.evenements.edit', ['event' => $groupEvent]);
    }

    public function update(Request $request, GroupEvent $groupEvent)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable',
        ]);

        // Normalize event_time if provided (accept H:i or datetime)
        if (!empty($data['event_time'])) {
            // allow time-only input (H:i) or full datetime
            try {
                $data['event_time'] = \Carbon\Carbon::parse($data['event_time'])->format('H:i:00');
            } catch (\Exception $e) {
                unset($data['event_time']);
            }
        }

        $groupEvent->update($data);

        return redirect()->route('admin.evenements.index')->with('success', 'Événement mis à jour');
    }

    public function destroy(GroupEvent $groupEvent)
    {
        $groupEvent->delete();
        return redirect()->route('admin.evenements.index')->with('success', 'Événement supprimé');
    }

    /**
     * Get total participants across all events
     */
    private function getTotalEventParticipants()
    {
        try {
            // Count unique users in chat messages across events
            return \DB::table('event_chat_messages')
                ->distinct('user_id')
                ->count('user_id');
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get total chat messages across all events
     */
    private function getTotalEventMessages()
    {
        try {
            return \DB::table('event_chat_messages')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get statistics for a specific event
     */
    public function getEventStats(GroupEvent $event)
    {
        $participants = \DB::table('event_chat_messages')
            ->where('group_event_id', $event->id)
            ->distinct('user_id')
            ->count('user_id');

        $messages = \DB::table('event_chat_messages')
            ->where('group_event_id', $event->id)
            ->count();

        $flaggedMessages = \DB::table('event_chat_messages')
            ->where('group_event_id', $event->id)
            ->where('moderation_status', 'flagged')
            ->count();

        return [
            'participants' => $participants,
            'messages' => $messages,
            'flagged_messages' => $flaggedMessages,
            'approved_messages' => $messages - $flaggedMessages,
        ];
    }

    /**
     * Export events with statistics as CSV
     */
    public function exportCSV(Request $request)
    {
        $query = GroupEvent::query();
        
        if ($request->input('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $events = $query->orderByDesc('event_date')->get();

        $response = new StreamedResponse(function () use ($events) {
            $handle = fopen('php://output', 'w');
            
            // Write BOM for UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($handle, [
                'ID',
                'Titre',
                'Groupe',
                'Date',
                'Heure',
                'Description',
                'Participants',
                'Messages Chat',
                'Messages Approuvés',
                'Messages Signalés',
                'Engagement',
                'Date Création'
            ]);

            // CSV Data
            foreach ($events as $event) {
                $stats = $this->getEventStats($event);
                $engagement = $stats['messages'] > 0 ? round(($stats['approved_messages'] / $stats['messages']) * 100, 2) : 0;

                fputcsv($handle, [
                    $event->id,
                    $event->title,
                    $event->readingGroup->name ?? 'N/A',
                    $event->event_date ? $event->event_date->format('Y-m-d') : 'N/A',
                    $event->event_time ? $event->event_time->format('H:i') : 'N/A',
                    $event->description,
                    $stats['participants'],
                    $stats['messages'],
                    $stats['approved_messages'],
                    $stats['flagged_messages'],
                    $engagement . '%',
                    $event->created_at->format('Y-m-d H:i')
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="events_export_' . date('Y-m-d_His') . '.csv"');

        return $response;
    }
}
