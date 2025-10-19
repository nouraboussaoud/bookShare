<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ChatModerationService;

class ModerationStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'moderation:stats {--date= : Show stats for specific date (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display chat moderation statistics and AI usage metrics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $moderationService = app(ChatModerationService::class);
        $stats = $moderationService->getModerationStats();

        $this->info('🤖 Chat Moderation Statistics');
        $this->line('================================');

        // AI API Stats
        $this->newLine();
        $this->info('🔗 AI API Usage:');
        $this->table(
            ['Metric', 'Today', 'Yesterday'],
            [
                ['API Calls', $stats['ai_api']['calls_today'], $stats['ai_api']['calls_yesterday']],
                ['Rate Limited', $stats['ai_api']['rate_limited_today'], '-'],
            ]
        );

        // Message Processing Stats
        $this->newLine();
        $this->info('💬 Message Processing:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Messages', $stats['messages_processed']['total_today']],
                ['AI Triggered', $stats['messages_processed']['ai_triggered_today']],
                ['Rejected', $stats['messages_processed']['rejected_today']],
                ['Flagged', $stats['messages_processed']['flagged_today']],
            ]
        );

        // Efficiency Metrics
        $this->newLine();
        $this->info('📊 Efficiency Metrics:');
        $this->table(
            ['Metric', 'Value'],
            [
                ['AI Usage Rate', $stats['efficiency']['ai_usage_rate'] . '%'],
                ['Rejection Rate', $stats['efficiency']['rejection_rate'] . '%'],
            ]
        );

        // Additional Info
        $this->newLine();
        $this->info('ℹ️  Additional Information:');
        $lastCall = $stats['ai_api']['last_call'];
        if ($lastCall > 0) {
            $this->line('Last AI API call: ' . date('Y-m-d H:i:s', $lastCall));
            $this->line('Time since last call: ' . (time() - $lastCall) . ' seconds ago');
        } else {
            $this->line('No AI API calls recorded yet today');
        }

        // Recommendations
        $this->newLine();
        $this->info('💡 Recommendations:');
        $aiUsageRate = $stats['efficiency']['ai_usage_rate'];

        if ($aiUsageRate > 20) {
            $this->warn('⚠️  High AI usage rate (' . $aiUsageRate . '%). Consider making criteria more restrictive.');
        } elseif ($aiUsageRate < 5) {
            $this->warn('⚠️  Low AI usage rate (' . $aiUsageRate . '%). AI might be underutilized for ambiguous content.');
        } else {
            $this->info('✅ AI usage rate is within optimal range (5-20%).');
        }

        if ($stats['ai_api']['calls_today'] >= 80) {
            $this->warn('⚠️  Approaching daily API limit (' . $stats['ai_api']['calls_today'] . '/100 calls).');
        }
    }
}
