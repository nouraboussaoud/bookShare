<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReadingGroup;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GroupManagementController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $q = $request->input('q');
        $perPage = (int) $request->input('per_page', 20);

        $query = ReadingGroup::query();
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // include members count if relation exists
        $query = $query->withCount(['members']);

        $groups = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // KPIs
        $totalGroups = ReadingGroup::count();
        $totalMembers = ReadingGroup::withCount('members')->get()->sum('members_count');
        $totalEvents = $this->getTotalGroupEvents();
        $totalChatActivity = $this->getTotalGroupChatActivity();

        return view('admin.groupes.index', compact('groups', 'totalGroups', 'totalMembers', 'totalEvents', 'totalChatActivity', 'q', 'perPage'));
    }

    public function show(ReadingGroup $readingGroup)
    {
        return view('admin.groupes.show', ['group' => $readingGroup]);
    }

    public function edit(ReadingGroup $readingGroup)
    {
        return view('admin.groupes.edit', ['group' => $readingGroup]);
    }

    public function update(Request $request, ReadingGroup $readingGroup)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $readingGroup->update($data);

        return redirect()->route('admin.groupes.index')->with('success', 'Groupe mis à jour');
    }

    public function destroy(ReadingGroup $readingGroup)
    {
        $readingGroup->delete();
        return redirect()->route('admin.groupes.index')->with('success', 'Groupe supprimé');
    }

    /**
     * Get total events across all groups
     */
    private function getTotalGroupEvents()
    {
        try {
            return \DB::table('group_events')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get total chat activity across all group events
     */
    private function getTotalGroupChatActivity()
    {
        try {
            return \DB::table('event_chat_messages')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get statistics for a specific group
     */
    public function getGroupStats(ReadingGroup $group)
    {
        $events = \DB::table('group_events')
            ->where('reading_group_id', $group->id)
            ->count();

        $chatMessages = \DB::table('event_chat_messages')
            ->whereIn('group_event_id', 
                \DB::table('group_events')
                    ->where('reading_group_id', $group->id)
                    ->pluck('id')
            )
            ->count();

        $members = $group->members()->count();

        $avgEngagement = $members > 0 && $events > 0 
            ? round($chatMessages / ($members * $events), 2)
            : 0;

        return [
            'events' => $events,
            'chat_messages' => $chatMessages,
            'members' => $members,
            'avg_engagement' => $avgEngagement,
        ];
    }

    /**
     * Export groups with statistics as CSV
     */
    public function exportCSV(Request $request)
    {
        $query = ReadingGroup::query();
        
        if ($request->input('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        $groups = $query->orderBy('created_at', 'desc')->get();

        $response = new StreamedResponse(function () use ($groups) {
            $handle = fopen('php://output', 'w');
            
            // Write BOM for UTF-8
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($handle, [
                'ID',
                'Nom du Groupe',
                'Description',
                'Nombre de Membres',
                'Nombre d\'Événements',
                'Messages Chat',
                'Engagement Moyen',
                'Date Création',
                'Propriétaire',
                'Statut'
            ]);

            // CSV Data
            foreach ($groups as $group) {
                $stats = $this->getGroupStats($group);
                $owner = $group->owner ? $group->owner->name : 'N/A';

                fputcsv($handle, [
                    $group->id,
                    $group->name,
                    $group->description,
                    $stats['members'],
                    $stats['events'],
                    $stats['chat_messages'],
                    $stats['avg_engagement'],
                    $group->created_at->format('Y-m-d H:i'),
                    $owner,
                    'Actif'
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="groups_export_' . date('Y-m-d_His') . '.csv"');

        return $response;
    }
}
