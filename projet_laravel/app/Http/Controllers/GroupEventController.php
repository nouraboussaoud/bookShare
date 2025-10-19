<?php

namespace App\Http\Controllers;

use App\Models\GroupEvent;
use App\Models\ReadingGroup;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GroupEventController extends Controller
{
    use AuthorizesRequests;

    /**
     * List all events for a reading group
     */
    public function index(ReadingGroup $readingGroup)
    {
        $this->authorize('view', $readingGroup);

        $upcomingEvents = $readingGroup->upcomingEvents()->get();
        $pastEvents = $readingGroup->pastEvents()->get();

        return view('groups.events.index', compact('readingGroup', 'upcomingEvents', 'pastEvents'));
    }

    /**
     * Show create event form
     */
    public function create(ReadingGroup $readingGroup)
    {
        $this->authorize('manageMembership', $readingGroup);
        return view('groups.events.create', compact('readingGroup'));
    }

    /**
     * Store a new event
     */
    public function store(Request $request, ReadingGroup $readingGroup)
    {
        $this->authorize('manageMembership', $readingGroup);

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'event_date'     => 'required|date|after_or_equal:today',
            'event_time'     => 'nullable|date_format:H:i',
            'location'       => 'nullable|string|max:255',
            'max_attendees'  => 'nullable|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:15|max:480', // 15 minutes to 8 hours
        ]);

        $event = $readingGroup->events()->create([
            'title'          => $data['title'],
            'description'    => $data['description'],
            'event_date'     => $data['event_date'],
            'event_time'     => $data['event_time'],
            'location'       => $data['location'],
            'max_attendees'  => $data['max_attendees'],
            'duration_minutes' => $data['duration_minutes'] ?? 120,
            'created_by'     => auth()->id(),
        ]);

        return redirect()
            ->route('reading-groups.events.show', [$readingGroup, $event])
            ->with('status', 'Event created successfully!');
    }

    /**
     * Show event details
     */
    public function show(ReadingGroup $readingGroup, GroupEvent $event)
    {
        $this->authorize('view', $readingGroup);

        if ($event->reading_group_id !== $readingGroup->id) {
            abort(404);
        }

        $event->load(['creator', 'attendees']);
        
        // Check if current user is attending (if authenticated)
        $isAttending = false;
        $attendanceStatus = null;
        
        if (auth()->check()) {
            $attendance = $event->attendees()
                ->where('user_id', auth()->id())
                ->first();
            if ($attendance) {
                $isAttending = true;
                $attendanceStatus = $attendance->pivot->status;
            }
        }

        return view('groups.events.show', compact('readingGroup', 'event', 'isAttending', 'attendanceStatus'));
    }

    /**
     * Show edit event form
     */
    public function edit(ReadingGroup $readingGroup, GroupEvent $event)
    {
        $this->authorize('manageMembership', $readingGroup);

        if ($event->reading_group_id !== $readingGroup->id) {
            abort(404);
        }

        return view('groups.events.edit', compact('readingGroup', 'event'));
    }

    /**
     * Update an event
     */
    public function update(Request $request, ReadingGroup $readingGroup, GroupEvent $event)
    {
        $this->authorize('manageMembership', $readingGroup);

        if ($event->reading_group_id !== $readingGroup->id) {
            abort(404);
        }

        $data = $request->validate([
            'title'          => 'sometimes|required|string|max:255',
            'description'    => 'nullable|string',
            'event_date'     => 'sometimes|required|date|after_or_equal:today',
            'event_time'     => 'nullable|date_format:H:i',
            'location'       => 'nullable|string|max:255',
            'max_attendees'  => 'nullable|integer|min:1',
            'duration_minutes' => 'nullable|integer|min:15|max:480',
        ]);

        $event->update($data);

        return redirect()
            ->route('reading-groups.events.show', [$readingGroup, $event])
            ->with('status', 'Event updated successfully!');
    }

    /**
     * Delete an event
     */
    public function destroy(ReadingGroup $readingGroup, GroupEvent $event)
    {
        $this->authorize('manageMembership', $readingGroup);

        if ($event->reading_group_id !== $readingGroup->id) {
            abort(404);
        }

        $event->delete();

        return redirect()
            ->route('reading-groups.events.index', $readingGroup)
            ->with('status', 'Event deleted successfully!');
    }

    /**
     * Join an event
     */
    public function joinEvent(Request $request, ReadingGroup $readingGroup, GroupEvent $event)
    {
        if ($event->reading_group_id !== $readingGroup->id) {
            abort(404);
        }

        // Check if already attending
        $existing = $event->attendees()->where('user_id', auth()->id())->first();
        if ($existing) {
            $message = 'You are already registered for this event.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'already_attending'], 422);
            }
            return redirect()->route('reading-groups.events.show', [$readingGroup, $event])->with('error', $message);
        }

        // Check max attendees
        if ($event->max_attendees && $event->confirmedAttendeesCount() >= $event->max_attendees) {
            $message = 'This event is full.';
            if ($request->wantsJson()) {
                return response()->json(['status' => 'event_full'], 422);
            }
            return redirect()->route('reading-groups.events.show', [$readingGroup, $event])->with('error', $message);
        }

        $event->attendees()->attach(auth()->id(), [
            'status' => 'confirmed',
            'joined_at' => now(),
        ]);

        $message = 'You have registered for this event!';
        if ($request->wantsJson()) {
            return response()->json(['status' => 'joined'], 200);
        }
        return redirect()->route('reading-groups.events.show', [$readingGroup, $event])->with('status', $message);
    }

    /**
     * Leave an event (or remove another user if group owner)
     */
    public function leaveEvent(Request $request, ReadingGroup $readingGroup, GroupEvent $event)
    {
        if ($event->reading_group_id !== $readingGroup->id) {
            abort(404);
        }

        $userId = $request->input('user_id', auth()->id());
        
        // Only allow removing others if user is group owner
        if ($userId !== auth()->id()) {
            $this->authorize('manageMembership', $readingGroup);
        }

        $event->attendees()->detach($userId);

        $message = $userId === auth()->id() 
            ? 'You have unregistered from this event.'
            : 'Participant removed from the event.';
            
        if ($request->wantsJson()) {
            return response()->json(['status' => 'left'], 200);
        }
        return redirect()->route('reading-groups.events.show', [$readingGroup, $event])->with('status', $message);
    }
}
