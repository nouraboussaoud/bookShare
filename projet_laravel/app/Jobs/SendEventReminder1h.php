<?php

namespace App\Jobs;

use App\Models\GroupEvent;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEventReminder1h implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    /**
     * Create a new job instance.
     */
    public function __construct(GroupEvent $event)
    {
        $this->event = $event;
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationService $notificationService): void
    {
        // Only send reminder if event is still upcoming
        if (!$this->event->isUpcoming()) {
            return;
        }

        // Send 1-hour reminder notifications
        $notificationService->notifyEventReminder1h($this->event);
    }
}
