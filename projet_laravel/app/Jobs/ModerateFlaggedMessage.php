<?php

namespace App\Jobs;

use App\Models\EventChatMessage;
use App\Services\ChatModerationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ModerateFlaggedMessage implements ShouldQueue
{
    use Queueable;

    protected $messageId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * Execute the job.
     */
    public function handle(ChatModerationService $moderationService): void
    {
        Log::info('Starting ModerateFlaggedMessage job', ['message_id' => $this->messageId]);

        $message = EventChatMessage::find($this->messageId);

        if (!$message) {
            Log::warning('Flagged message not found for moderation', ['message_id' => $this->messageId]);
            return;
        }

        Log::info('Found flagged message for moderation', [
            'message_id' => $this->messageId,
            'current_status' => $message->moderation_status,
            'message_preview' => substr($message->message, 0, 50) . (strlen($message->message) > 50 ? '...' : '')
        ]);

        // Only moderate messages that are still flagged
        if ($message->moderation_status !== 'flagged') {
            Log::info('Message status changed, skipping AI moderation', [
                'message_id' => $this->messageId,
                'current_status' => $message->moderation_status
            ]);
            return;
        }

        try {
            // Perform AI moderation on the flagged message
            $aiResult = $moderationService->moderateFlaggedMessage($message->message);

            if ($aiResult) {
                if ($aiResult['action'] === 'delete') {
                    // Delete the message if AI determines it should be rejected
                    $message->delete();

                    Log::warning('MESSAGE DELETED by AI moderation', [
                        'message_id' => $this->messageId,
                        'reason' => $aiResult['reason'],
                        'confidence' => $aiResult['confidence'] ?? 0.5,
                        'message_preview' => substr($message->message, 0, 30) . '...'
                    ]);
                } else {
                    // Update the message status based on AI result
                    $newStatus = $aiResult['status'] === 'approved' ? 'approved' : 'flagged';
                    $message->update([
                        'moderation_status' => $newStatus,
                        'moderation_reason' => $aiResult['reason'],
                        'is_moderated' => true
                    ]);

                    Log::info('Message status updated by AI moderation', [
                        'message_id' => $this->messageId,
                        'old_status' => 'flagged',
                        'new_status' => $newStatus,
                        'reason' => $aiResult['reason'],
                        'confidence' => $aiResult['confidence'] ?? 0.5
                    ]);
                }
            } else {
                Log::warning('AI moderation returned null result', ['message_id' => $this->messageId]);
            }
        } catch (\Exception $e) {
            Log::error('AI moderation job failed', [
                'message_id' => $this->messageId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // On failure, keep the message but mark it as needing manual review
            $message->update([
                'moderation_reason' => 'AI moderation failed - requires manual review',
                'is_moderated' => true
            ]);

            Log::info('Message marked for manual review due to AI failure', [
                'message_id' => $this->messageId
            ]);
        }

        Log::info('ModerateFlaggedMessage job completed', ['message_id' => $this->messageId]);
    }
}
