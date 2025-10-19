<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupTypingStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-typing-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old typing statuses from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning up old typing statuses...');

        $deletedCount = \App\Models\TypingStatus::cleanup();

        $this->info("Deleted {$deletedCount} old typing status(es).");

        return 0;
    }
}
