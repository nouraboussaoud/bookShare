<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\GroupEvent;
use App\Models\EventChatMessage;
use App\Models\TypingStatus;
use App\Services\ChatModerationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class EventChatController extends Controller
{
    protected $moderationService;

    public function __construct(ChatModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }

    /**
     * Get chat messages for an event
     */
    public function getMessages(GroupEvent $event)
    {
        $messages = $event->approvedChatMessages()
            ->with('user:id,name,email')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'user' => [
                        'id' => $message->user->id,
                        'name' => $message->user->name,
                        'avatar' => strtoupper(substr($message->user->name, 0, 2))
                    ],
                    'created_at' => $message->created_at->diffForHumans(),
                    'timestamp' => $message->created_at->toIso8601String(),
                    'is_own' => $message->user_id === auth()->id(),
                    'moderation_status' => $message->moderation_status,
                    'ai_used' => $message->ai_used,
                    'reply_to' => $message->reply_to_message_id ? [
                        'user' => $message->reply_to_user,
                        'message' => $message->reply_to_content
                    ] : null
                ];
            });

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }

    /**
     * Post a new chat message
     */
    public function postMessage(Request $request, GroupEvent $event)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'reply_to' => 'nullable|string',
            'reply_message' => 'nullable|string',
            'reply_user' => 'nullable|string'
        ]);

        // Sanitize message
        $sanitizedMessage = $this->moderationService->sanitizeMessage($request->message);

        // Moderate message
        $moderation = $this->moderationService->moderateMessage($sanitizedMessage);

        // Create message
        $chatMessage = EventChatMessage::create([
            'group_event_id' => $event->id,
            'user_id' => auth()->id(),
            'message' => $sanitizedMessage,
            'is_moderated' => !$moderation['approved'] || $moderation['status'] !== 'approved',
            'ai_used' => $moderation['ai_used'] ?? false,
            'moderation_reason' => $moderation['reason'],
            'moderation_status' => $moderation['status'],
            'reply_to_message_id' => $request->reply_to,
            'reply_to_user' => $request->reply_user,
            'reply_to_content' => $request->reply_message
        ]);

        // Load user relation
        $chatMessage->load('user:id,name,email');

        // Broadcast the message if it's approved
        if ($moderation['status'] === 'approved') {
            broadcast(new ChatMessageSent($chatMessage))->toOthers();
        }

        // For flagged messages, run AI moderation synchronously for debugging
        if ($moderation['status'] === 'flagged') {
            \Log::info('Running synchronous AI moderation for flagged message', [
                'message_id' => $chatMessage->id,
                'message_preview' => substr($chatMessage->message, 0, 50) . (strlen($chatMessage->message) > 50 ? '...' : '')
            ]);

            try {
                $aiResult = $this->moderationService->moderateFlaggedMessage($chatMessage->message);

                if ($aiResult) {
                    if ($aiResult['action'] === 'delete') {
                        $chatMessage->delete();
                        \Log::warning('Message deleted by synchronous AI moderation', [
                            'message_id' => $chatMessage->id,
                            'reason' => $aiResult['reason'],
                            'confidence' => $aiResult['confidence'] ?? 0.5
                        ]);

                        return response()->json([
                            'success' => false,
                            'message' => 'Message was rejected by AI moderation and has been removed.'
                        ], 422);
                    } else {
                        $chatMessage->update([
                            'moderation_status' => $aiResult['status'] === 'approved' ? 'approved' : 'flagged',
                            'moderation_reason' => $aiResult['reason'],
                            'is_moderated' => true
                        ]);

                        \Log::info('Message updated by synchronous AI moderation', [
                            'message_id' => $chatMessage->id,
                            'new_status' => $chatMessage->moderation_status,
                            'reason' => $aiResult['reason']
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Synchronous AI moderation failed', [
                    'message_id' => $chatMessage->id,
                    'error' => $e->getMessage()
                ]);

                // Keep the message but mark for manual review
                $chatMessage->update([
                    'moderation_reason' => 'AI moderation failed - requires manual review',
                    'is_moderated' => true
                ]);
            }
        }

        // Return response
        if ($moderation['status'] === 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Message rejected: ' . $moderation['reason']
            ], 422);
        }

        if ($moderation['status'] === 'flagged') {
            return response()->json([
                'success' => true,
                'message' => 'Message posted but flagged for review',
                'flagged' => true,
                'data' => [
                    'id' => $chatMessage->id,
                    'message' => $chatMessage->message,
                    'user' => [
                        'id' => $chatMessage->user->id,
                        'name' => $chatMessage->user->name,
                        'avatar' => strtoupper(substr($chatMessage->user->name, 0, 2))
                    ],
                    'created_at' => $chatMessage->created_at->diffForHumans(),
                    'timestamp' => $chatMessage->created_at->toIso8601String(),
                    'is_own' => true,
                    'ai_used' => $chatMessage->ai_used,
                    'reply_to' => $request->reply_to ? [
                        'user' => $request->reply_user,
                        'message' => $request->reply_message
                    ] : null
                ]
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Message posted successfully',
            'data' => [
                'id' => $chatMessage->id,
                'message' => $chatMessage->message,
                'user' => [
                    'id' => $chatMessage->user->id,
                    'name' => $chatMessage->user->name,
                    'avatar' => strtoupper(substr($chatMessage->user->name, 0, 2))
                ],
                'created_at' => $chatMessage->created_at->diffForHumans(),
                'timestamp' => $chatMessage->created_at->toIso8601String(),
                'is_own' => true,
                'ai_used' => $chatMessage->ai_used,
                'reply_to' => $request->reply_to ? [
                    'user' => $request->reply_user,
                    'message' => $request->reply_message
                ] : null
            ]
        ]);
    }

    /**
     * Update typing status for a user in an event chat
     */
    public function updateTypingStatus(Request $request, GroupEvent $event)
    {
        $request->validate([
            'typing' => 'required|boolean'
        ]);

        if ($request->typing) {
            // Update or create typing status
            TypingStatus::updateTyping(auth()->id(), $event->id);
        } else {
            // Remove typing status
            TypingStatus::where('user_id', auth()->id())
                       ->where('group_event_id', $event->id)
                       ->delete();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get typing status for an event chat
     */
    public function getTypingStatus(GroupEvent $event)
    {
        $currentUserId = auth()->id();

        // Get active typing users (excluding current user)
        $typingUsers = TypingStatus::where('group_event_id', $event->id)
            ->where('user_id', '!=', $currentUserId)
            ->active()
            ->with('user:id,name')
            ->get()
            ->map(function ($status) {
                return $status->user->name;
            });

        $isTyping = $typingUsers->isNotEmpty();
        $userName = $isTyping ? $typingUsers->first() : null;

        return response()->json([
            'typing' => $isTyping,
            'user_name' => $userName
        ]);
    }

    /**
     * Get chat statistics (for group owners)
     */
    public function getStatistics(GroupEvent $event)
    {
        // Check if user is group owner
        if ($event->readingGroup->owner_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $stats = [
            'total_messages' => $event->chatMessages()->count(),
            'approved_messages' => $event->chatMessages()->where('moderation_status', 'approved')->count(),
            'flagged_messages' => $event->chatMessages()->where('moderation_status', 'flagged')->count(),
            'rejected_messages' => $event->chatMessages()->where('moderation_status', 'rejected')->count(),
            'unique_users' => $event->chatMessages()->distinct('user_id')->count('user_id'),
        ];

        return response()->json([
            'success' => true,
            'statistics' => $stats
        ]);
    }

    /**
     * Show the chat page for an event
     */
    public function showChat(GroupEvent $event)
    {
        // Check that readingGroup relation exists
        if (!$event->readingGroup) {
            abort(404, 'Event reading group not found');
        }

        // Check if user can access this event's chat
        $canAccess = $event->readingGroup->members() && $event->readingGroup->members()->where('user_id', auth()->id())->exists()
                    || ($event->readingGroup->owner_id ?? null) === auth()->id();

        if (!$canAccess) {
            abort(403, 'You must be a member of this group to access the event chat.');
        }

        // Check if user is a confirmed attendee (allows chat access even when event is not active)
        $isConfirmedAttendee = $event->attendees()->where('user_id', auth()->id())->wherePivot('status', 'confirmed')->exists();

        // Allow access if event is active OR user is a confirmed attendee
        if (!$event->isActive() && !$isConfirmedAttendee) {
            return redirect()->route('reading-groups.events.show', [$event->readingGroup, $event])
                           ->with('error', 'Le chat n\'est disponible que pour les participants confirmés ou pendant l\'événement en cours.');
        }

        return view('groups.events.chat', compact('event'));
    }
}
