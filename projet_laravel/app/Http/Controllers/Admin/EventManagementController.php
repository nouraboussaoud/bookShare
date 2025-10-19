<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GroupEvent;
use Illuminate\Http\Request;

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

    return view('admin.evenements.index', compact('events', 'totalEvents', 'upcoming', 'q', 'perPage'));
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
}
