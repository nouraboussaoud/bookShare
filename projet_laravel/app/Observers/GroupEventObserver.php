<?php

namespace App\Observers;

use App\Jobs\AutoCloseEventPolls;
use App\Jobs\SendEventReminder1h;
use App\Jobs\SendEventReminder24h;
use App\Models\GroupEvent;
use Carbon\Carbon;

class GroupEventObserver
{
    /**
     * Handle the GroupEvent "created" event.
     */
    public function created(GroupEvent $event): void
    {
        $this->scheduleReminders($event);
    }

    /**
     * Handle the GroupEvent "updated" event.
     */
    public function updated(GroupEvent $event): void
    {
        // If event date/time was updated, reschedule reminders
        if ($event->wasChanged(['event_date', 'event_time'])) {
            $this->scheduleReminders($event);
        }
    }

    /**
     * Schedule reminder jobs for the event
     */
    private function scheduleReminders(GroupEvent $event): void
    {
        if (!$event->event_time || !$event->event_date) {
            return;
        }

        $eventDateTime = $event->event_date->setTimeFromTimeString($event->event_time->format('H:i:s'));

        // Schedule 24-hour reminder
        $reminder24hTime = $eventDateTime->copy()->subHours(24);
        if ($reminder24hTime->isFuture()) {
            SendEventReminder24h::dispatch($event)->delay($reminder24hTime);
        }

        // Schedule 1-hour reminder
        $reminder1hTime = $eventDateTime->copy()->subHour();
        if ($reminder1hTime->isFuture()) {
            SendEventReminder1h::dispatch($event)->delay($reminder1hTime);
        }

        // Schedule auto-close polls at event end time
        $durationMinutes = $event->duration_minutes ?? 120;
        $eventEndTime = $eventDateTime->copy()->addMinutes($durationMinutes);
        if ($eventEndTime->isFuture()) {
            AutoCloseEventPolls::dispatch($event)->delay($eventEndTime);
        }
    }

    /**
     * Handle the GroupEvent "deleted" event.
     */
    public function deleted(GroupEvent $event): void
    {
        // Cascade delete is handled by database, but you could add additional cleanup here
    }
}
