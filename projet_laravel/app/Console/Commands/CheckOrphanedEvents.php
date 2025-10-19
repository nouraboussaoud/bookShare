<?php

namespace App\Console\Commands;

use App\Models\GroupEvent;
use Illuminate\Console\Command;

class CheckOrphanedEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:check-orphaned {--delete : Delete orphaned events}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for events that are not associated with valid reading groups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for orphaned events...');

        // Find events where reading_group_id is null or the reading group doesn't exist
        $orphanedEvents = GroupEvent::whereNull('reading_group_id')
            ->orWhereDoesntHave('readingGroup')
            ->get();

        if ($orphanedEvents->isEmpty()) {
            $this->info('✅ No orphaned events found.');
            return;
        }

        $this->warn("Found {$orphanedEvents->count()} orphaned events:");

        foreach ($orphanedEvents as $event) {
            $this->line("- Event ID: {$event->id}, Title: '{$event->title}'");
        }

        if ($this->option('delete')) {
            if ($this->confirm('Delete these orphaned events?')) {
                $count = $orphanedEvents->count();
                GroupEvent::whereIn('id', $orphanedEvents->pluck('id'))->delete();
                $this->info("✅ Deleted {$count} orphaned events.");
            }
        } else {
            $this->info('Use --delete flag to remove orphaned events.');
        }
    }
}
