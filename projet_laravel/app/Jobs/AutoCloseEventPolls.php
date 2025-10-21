<?php

namespace App\Jobs;

use App\Models\GroupEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoCloseEventPolls implements ShouldQueue
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
     * Execute the job - Auto-close all active polls when event ends.
     */
    public function handle(): void
    {
        // Close all active polls for this event
        $this->event->closeAllPolls();
    }
}
