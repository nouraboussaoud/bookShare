@extends('layouts.app')

@section('title', 'Chat - ' . $event->title)

@push('styles')
<style>
    :root {
        --chat-primary: #6366f1;
        --chat-secondary: #8b5cf6;
        --chat-success: #10b981;
        --chat-warning: #f59e0b;
        --chat-error: #ef4444;
        --chat-bg: #f8fafc;
        --chat-surface: #ffffff;
        --chat-text: #1f2937;
        --chat-text-light: #6b7280;
        --chat-border: #e5e7eb;
    }

    .chat-container {
        height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
        background: var(--chat-bg);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--chat-border);
    }

    .chat-header {
        background: linear-gradient(135deg, var(--chat-primary), var(--chat-secondary));
        color: white;
        padding: 1.25rem 1.5rem;
        border-radius: 16px 16px 0 0;
        box-shadow: 0 2px 10px rgba(99, 102, 241, 0.2);
    }

    .chat-header-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .chat-header-subtitle {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background: var(--chat-bg);
        scroll-behavior: smooth;
    }

    .chat-messages::-webkit-scrollbar {
        width: 6px;
    }

    .chat-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    .chat-messages::-webkit-scrollbar-thumb {
        background: var(--chat-border);
        border-radius: 3px;
    }

    .chat-messages::-webkit-scrollbar-thumb:hover {
        background: var(--chat-text-light);
    }

    .message-group {
        margin-bottom: 1.5rem;
    }

    .message-group:last-child {
        margin-bottom: 0;
    }

    .message-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--chat-primary), var(--chat-secondary));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .message-bubble {
        max-width: 70%;
        margin-left: 0.75rem;
        position: relative;
    }

    .message-own .message-bubble {
        margin-left: auto;
        margin-right: 0.75rem;
    }

    .message-content {
        background: var(--chat-surface);
        padding: 0.875rem 1.125rem;
        border-radius: 18px 18px 18px 4px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--chat-border);
        word-wrap: break-word;
        position: relative;
        word-wrap: break-word;
        position: relative;
    }

    .message-own .message-content {
        background: linear-gradient(135deg, var(--chat-primary), var(--chat-secondary));
        color: white;
        border-radius: 18px 18px 4px 18px;
        border: none;
    }

    .message-actions {
        position: absolute;
        top: -10px;
        right: 10px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        padding: 4px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.2s;
        z-index: 10;
    }

    .message-own .message-actions {
        right: auto;
        left: 10px;
    }

    .message-bubble:hover .message-actions {
        opacity: 1;
        visibility: visible;
    }

    .action-btn {
        background: none;
        border: none;
        padding: 6px 8px;
        border-radius: 12px;
        color: var(--chat-text-light);
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .action-btn:hover {
        background: var(--chat-bg);
        color: var(--chat-text);
    }

    .reply-preview {
        background: var(--chat-bg);
        border-left: 3px solid var(--chat-primary);
        padding: 0.75rem 1rem;
        margin-bottom: 1rem;
        border-radius: 8px;
        position: relative;
    }

    .reply-preview-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
        font-size: 0.85rem;
        color: var(--chat-text-light);
    }

    .reply-preview-close {
        margin-left: auto;
        background: none;
        border: none;
        color: var(--chat-text-light);
        cursor: pointer;
        padding: 2px;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .reply-preview-close:hover {
        background: rgba(0, 0, 0, 0.1);
    }

    .reply-preview-text {
        font-size: 0.9rem;
        color: var(--chat-text);
        font-style: italic;
    }

    .reply-indicator {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        background: rgba(99, 102, 241, 0.05);
        border-left: 3px solid var(--chat-primary);
        border-radius: 6px;
        font-size: 0.85rem;
    }

    .reply-line {
        width: 2px;
        background: var(--chat-primary);
        border-radius: 1px;
        flex-shrink: 0;
    }

    .reply-content {
        flex: 1;
        min-width: 0;
    }

    .reply-user {
        font-weight: 600;
        color: var(--chat-primary);
        margin-bottom: 0.25rem;
    }

    .reply-text {
        color: var(--chat-text-light);
        font-style: italic;
        word-wrap: break-word;
        line-height: 1.3;
    }

    .message-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.375rem;
        font-size: 0.8rem;
    }

    .message-sender {
        font-weight: 600;
        color: var(--chat-text);
    }

    .message-own .message-sender {
        color: rgba(255, 255, 255, 0.9);
    }

    .message-time {
        color: var(--chat-text-light);
        font-size: 0.75rem;
    }

    .message-own .message-time {
        color: rgba(255, 255, 255, 0.7);
    }

    .message-text {
        line-height: 1.5;
        font-size: 0.95rem;
    }

    .message-status {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: 0.25rem;
        font-size: 0.7rem;
        opacity: 0.7;
    }

    .message-status i {
        margin-left: 0.25rem;
    }

    .message-flagged {
        border-left: 3px solid var(--chat-warning) !important;
    }

    .message-rejected {
        opacity: 0.6;
        background: #fef2f2 !important;
        border: 1px solid var(--chat-error) !important;
    }

    .typing-indicator {
        display: none;
        padding: 1rem 1.5rem;
        color: var(--chat-text-light);
        font-style: italic;
        font-size: 0.9rem;
    }

    .typing-indicator.show {
        display: block;
    }

    .typing-dots {
        display: inline-flex;
        gap: 2px;
        margin-left: 0.5rem;
    }

    .typing-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--chat-text-light);
        animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-8px); }
    }

    .chat-input-area {
        background: var(--chat-surface);
        border-top: 1px solid var(--chat-border);
        padding: 1.25rem 1.5rem;
        border-radius: 0 0 16px 16px;
    }

    .chat-input-container {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
        background: var(--chat-bg);
        border: 2px solid var(--chat-border);
        border-radius: 24px;
        padding: 0.5rem 0.75rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .chat-input-container:focus-within {
        border-color: var(--chat-primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .chat-input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        font-size: 0.95rem;
        line-height: 1.4;
        resize: none;
        min-height: 20px;
        max-height: 100px;
        font-family: inherit;
    }

    .chat-input::placeholder {
        color: var(--chat-text-light);
    }

    .send-button {
        background: linear-gradient(135deg, var(--chat-primary), var(--chat-secondary));
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        flex-shrink: 0;
    }

    .send-button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .send-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .empty-chat {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: var(--chat-text-light);
        text-align: center;
        padding: 2rem;
    }

    .empty-chat i {
        font-size: 4rem;
        margin-bottom: 1.5rem;
        opacity: 0.4;
        background: linear-gradient(135deg, var(--chat-primary), var(--chat-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .empty-chat h6 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--chat-text);
    }

    .empty-chat p {
        margin: 0;
        font-size: 0.9rem;
    }

    .event-info {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .event-info h6 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .event-info p {
        margin: 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .back-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
        backdrop-filter: blur(10px);
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .notification-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
        max-width: 400px;
        display: none;
    }

    .toast-message {
        background: white;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid var(--chat-border);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        animation: slideIn 0.3s ease-out;
    }

    .toast-icon {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .toast-icon.success { background: var(--chat-success); color: white; }
    .toast-icon.warning { background: var(--chat-warning); color: white; }
    .toast-icon.error { background: var(--chat-error); color: white; }
    .toast-icon.info { background: var(--chat-primary); color: white; }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .toast-text {
        font-size: 0.85rem;
        color: var(--chat-text-light);
        line-height: 1.4;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    @media (max-width: 768px) {
        .chat-container {
            height: calc(100vh - 150px);
            border-radius: 0;
        }

        .chat-header {
            border-radius: 0;
        }

        .chat-input-area {
            border-radius: 0;
        }

        .message-bubble {
            max-width: 85%;
        }

        .chat-messages {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-md-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Notification Toast Container -->
            <div class="notification-toast" id="notificationToast"></div>

            <div class="chat-container">
                <!-- Chat Header -->
                <div class="chat-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="chat-header-title mb-1">
                                <i class="fas fa-comments me-2"></i>
                                Discussion de l'événement
                            </h5>
                            <p class="chat-header-subtitle">{{ $event->title }}</p>
                        </div>
                        <a href="{{ route('reading-groups.events.show', [$event->readingGroup, $event]) }}" class="back-btn">
                            <i class="fas fa-arrow-left me-1"></i>Retour
                        </a>
                    </div>

                    <!-- Event Info -->
                    <div class="event-info mt-3">
                        <h6><i class="fas fa-calendar-alt me-2"></i>Événement en cours</h6>
                        <p>
                            <i class="fas fa-clock me-1"></i>
                            {{ $event->event_date->format('d/m/Y') }}
                            @if($event->event_time)
                                à {{ $event->event_time->format('H:i') }}
                            @endif
                            @if($event->duration_minutes)
                                <br><i class="fas fa-hourglass-half me-1"></i>Durée: {{ $event->duration_minutes }} minutes
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Messages Area -->
                <div class="chat-messages" id="chatMessages">
                    <div class="empty-chat" id="emptyState">
                        <i class="fas fa-comments"></i>
                        <h6>Aucun message pour le moment</h6>
                        <p>Soyez le premier à commencer la conversation !</p>
                    </div>
                </div>

                <!-- Typing Indicator -->
                <div class="typing-indicator" id="typingIndicator">
                    <span id="typingUser"></span> est en train d'écrire
                    <div class="typing-dots">
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                        <div class="typing-dot"></div>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="chat-input-area">
                    <!-- Reply Preview -->
                    <div class="reply-preview" id="replyPreview" style="display: none;">
                        <div class="reply-preview-header">
                            <i class="fas fa-reply"></i>
                            <span>Réponse à <strong id="replyUser"></strong></span>
                            <button class="reply-preview-close" onclick="cancelReply()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="reply-preview-text" id="replyText"></div>
                    </div>

                    <div class="chat-input-container">
                        <textarea
                            class="chat-input"
                            id="messageInput"
                            placeholder="Tapez votre message..."
                            maxlength="1000"
                            rows="1"
                            required></textarea>
                        <button type="button" class="send-button" id="sendButton" disabled>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventId = {{ $event->id }};
    const messagesContainer = document.getElementById('chatMessages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    const emptyState = document.getElementById('emptyState');
    const typingIndicator = document.getElementById('typingIndicator');
    const typingUser = document.getElementById('typingUser');
    const notificationToast = document.getElementById('notificationToast');

    let lastMessageId = 0;
    let isTyping = false;
    let typingTimeout;
    let messageStatus = {}; // Track message sending status
    let replyTo = null; // Track reply information

    // Initialize
    loadMessages();
    setupInputHandling();
    setupAutoRefresh();

    function setupInputHandling() {
        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 100) + 'px';

            // Enable/disable send button
            sendButton.disabled = this.value.trim().length === 0;

            // Handle typing indicator
            handleTyping();
        });

        // Send on Enter (but allow Shift+Enter for new line)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Send button click
        sendButton.addEventListener('click', sendMessage);
    }

    function setupAutoRefresh() {
        // Auto-refresh messages every 3 seconds
        setInterval(loadMessages, 3000);

        // Check typing status every 2 seconds
        setInterval(checkTypingStatus, 2000);
    }

    function handleTyping() {
        if (!isTyping) {
            isTyping = true;
            sendTypingStatus(true);
        }

        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            isTyping = false;
            sendTypingStatus(false);
        }, 1000);
    }

    function sendTypingStatus(typing) {
        fetch(`{{ route("events.chat.typing", ":event") }}`.replace(':event', eventId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ typing: typing })
        }).catch(error => {
            console.error('Error sending typing status:', error);
        });
    }

    function checkTypingStatus() {
        fetch(`{{ route("events.chat.typing-status", ":event") }}`.replace(':event', eventId))
            .then(response => response.json())
            .then(data => {
                if (data.typing && data.user_name) {
                    showTypingIndicator(data.user_name);
                } else {
                    hideTypingIndicator();
                }
            })
            .catch(error => {
                console.error('Error checking typing status:', error);
            });
    }

    function showTypingIndicator(userName) {
        typingUser.textContent = userName;
        typingIndicator.classList.add('show');
    }

    function hideTypingIndicator() {
        typingIndicator.classList.remove('show');
    }

    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        const tempMessageId = 'temp_' + Date.now();
        messageStatus[tempMessageId] = 'sending';

        // Prepare message data
        let messageData = { message: message };
        if (replyTo) {
            messageData.reply_to = replyTo.id;
            messageData.reply_message = replyTo.message;
            messageData.reply_user = replyTo.user;
        }

        // Add temporary message to UI
        addMessageToUI({
            id: tempMessageId,
            message: message,
            user: {
                id: {{ auth()->id() }},
                name: '{{ auth()->user()->name }}',
                avatar: '{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}'
            },
            created_at: 'Envoi en cours...',
            timestamp: new Date().toISOString(),
            is_own: true,
            status: 'sending',
            reply_to: replyTo ? {
                user: replyTo.user,
                message: replyTo.message
            } : null
        });

        // Clear input and reply
        messageInput.value = '';
        messageInput.style.height = 'auto';
        sendButton.disabled = true;
        cancelReply();

        // Send message
        fetch(`{{ route("events.chat.messages", ":event") }}`.replace(':event', eventId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(messageData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageStatus[tempMessageId] = 'sent';
                updateMessageStatus(tempMessageId, 'sent');

                // Show success notification if flagged
                if (data.flagged) {
                    showNotification('warning', 'Message signalé',
                        'Votre message a été envoyé mais signalé pour modération.');
                }

                loadMessages(); // Refresh to get real message
            } else {
                messageStatus[tempMessageId] = 'failed';
                updateMessageStatus(tempMessageId, 'failed');
                showNotification('error', 'Erreur d\'envoi',
                    data.message || 'Impossible d\'envoyer le message.');

                // Remove failed message after delay
                setTimeout(() => {
                    removeMessageFromUI(tempMessageId);
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            messageStatus[tempMessageId] = 'failed';
            updateMessageStatus(tempMessageId, 'failed');
            showNotification('error', 'Erreur d\'envoi',
                'Une erreur s\'est produite lors de l\'envoi du message.');

            // Remove failed message after delay
            setTimeout(() => {
                removeMessageFromUI(tempMessageId);
            }, 3000);
        });
    }

    function loadMessages() {
        fetch(`{{ route("events.chat.messages", ":event") }}`.replace(':event', eventId))
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayMessages(data.messages);
                }
            })
            .catch(error => {
                console.error('Error loading messages:', error);
            });
    }

    function displayMessages(messages) {
        if (messages.length === 0) {
            emptyState.style.display = 'flex';
            messagesContainer.innerHTML = '';
            messagesContainer.appendChild(emptyState);
            return;
        }

        emptyState.style.display = 'none';

        // Group messages by user and time
        const groupedMessages = groupMessagesByUser(messages);

        // Only update if there are new messages
        if (messages.length > 0 && messages[messages.length - 1].id !== lastMessageId) {
            messagesContainer.innerHTML = '';

            groupedMessages.forEach(group => {
                const groupElement = createMessageGroup(group);
                messagesContainer.appendChild(groupElement);
            });

            lastMessageId = messages[messages.length - 1].id;

            // Scroll to bottom
            scrollToBottom();
        }
    }

    function groupMessagesByUser(messages) {
        const groups = [];
        let currentGroup = null;

        messages.forEach(message => {
            const shouldGroup = currentGroup &&
                currentGroup.user.id === message.user.id &&
                (new Date(message.timestamp) - new Date(currentGroup.messages[currentGroup.messages.length - 1].timestamp)) < 300000; // 5 minutes

            if (shouldGroup) {
                currentGroup.messages.push(message);
            } else {
                if (currentGroup) {
                    groups.push(currentGroup);
                }
                currentGroup = {
                    user: message.user,
                    messages: [message]
                };
            }
        });

        if (currentGroup) {
            groups.push(currentGroup);
        }

        return groups;
    }

    function createMessageGroup(group) {
        const groupDiv = document.createElement('div');
        groupDiv.className = 'message-group';

        group.messages.forEach((message, index) => {
            const messageElement = createMessageElement(message, index === 0);
            groupDiv.appendChild(messageElement);
        });

        return groupDiv;
    }

    function createMessageElement(message, showAvatar = true) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chat-message ${message.is_own ? 'message-own' : ''}`;
        messageDiv.dataset.messageId = message.id;

        let moderationClass = '';
        if (message.moderation_status === 'flagged') {
            moderationClass = 'message-flagged';
        } else if (message.moderation_status === 'rejected') {
            moderationClass = 'message-rejected';
        }

        const replyHtml = message.reply_to ? `
            <div class="reply-indicator">
                <div class="reply-line"></div>
                <div class="reply-content">
                    <div class="reply-user">${message.reply_to.user}</div>
                    <div class="reply-text">${escapeHtml(message.reply_to.message)}</div>
                </div>
            </div>
        ` : '';

        messageDiv.innerHTML = `
            ${showAvatar ? `<div class="message-avatar">${message.user.avatar}</div>` : '<div class="message-avatar" style="visibility: hidden;"></div>'}
            <div class="message-bubble">
                <div class="message-content ${moderationClass}">
                    <div class="message-actions">
                        <button class="action-btn reply-btn" onclick="replyToMessage('${message.id}', '${message.user.name}', '${escapeHtml(message.message)}')" title="Répondre">
                            <i class="fas fa-reply"></i>
                        </button>
                    </div>
                    ${!message.is_own ? `<div class="message-meta">
                        <span class="message-sender">${message.user.name}</span>
                        <span class="message-time">${message.created_at}</span>
                    </div>` : ''}
                    ${replyHtml}
                    <div class="message-text">${escapeHtml(message.message)}</div>
                    ${message.is_own ? `<div class="message-status">
                        <span class="message-time">${message.created_at}</span>
                        ${getStatusIcon(message.status || 'sent')}
                    </div>` : ''}
                </div>
            </div>
        `;

        return messageDiv;
    }

    function getStatusIcon(status) {
        const icons = {
            'sending': '<i class="fas fa-circle-notch fa-spin" style="color: #6b7280;"></i>',
            'sent': '<i class="fas fa-check" style="color: #10b981;"></i>',
            'delivered': '<i class="fas fa-check-double" style="color: #10b981;"></i>',
            'failed': '<i class="fas fa-exclamation-triangle" style="color: #ef4444;"></i>'
        };
        return icons[status] || '';
    }

    function updateMessageStatus(messageId, status) {
        const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageElement) {
            const statusElement = messageElement.querySelector('.message-status');
            if (statusElement) {
                const iconElement = statusElement.querySelector('i');
                if (iconElement) {
                    iconElement.outerHTML = getStatusIcon(status);
                }
            }
        }
    }

    function addMessageToUI(message) {
        const messageElement = createMessageElement(message, true);
        messagesContainer.appendChild(messageElement);
        scrollToBottom();
    }

    function removeMessageFromUI(messageId) {
        const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
        if (messageElement) {
            messageElement.remove();
        }
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function showNotification(type, title, message) {
        const toast = document.createElement('div');
        toast.className = 'toast-message';

        toast.innerHTML = `
            <div class="toast-icon ${type}">
                <i class="${getNotificationIcon(type)}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                <div class="toast-text">${message}</div>
            </div>
        `;

        notificationToast.appendChild(toast);
        notificationToast.style.display = 'block';

        // Auto-remove after 5 seconds
        setTimeout(() => {
            toast.remove();
            if (notificationToast.children.length === 0) {
                notificationToast.style.display = 'none';
            }
        }, 5000);
    }

    function getNotificationIcon(type) {
        const icons = {
            'success': 'fas fa-check',
            'warning': 'fas fa-exclamation-triangle',
            'error': 'fas fa-times',
            'info': 'fas fa-info'
        };
        return icons[type] || 'fas fa-info';
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Focus input on page load
    messageInput.focus();

    // Global functions for message actions
    window.replyToMessage = function(messageId, userName, messageText) {
        replyTo = {
            id: messageId,
            user: userName,
            message: messageText.substring(0, 100) + (messageText.length > 100 ? '...' : '')
        };

        document.getElementById('replyUser').textContent = userName;
        document.getElementById('replyText').textContent = messageText;
        document.getElementById('replyPreview').style.display = 'block';

        messageInput.focus();
        messageInput.placeholder = `Répondre à ${userName}...`;
    };

    window.cancelReply = function() {
        replyTo = null;
        document.getElementById('replyPreview').style.display = 'none';
        messageInput.placeholder = 'Tapez votre message...';
    };

    // Listen for real-time messages (only if Echo is available)
    if (typeof Echo !== 'undefined') {
        try {
            Echo.channel('event-chat.{{ $event->id }}')
                .listen('.message.sent', (e) => {
                    // Add the received message to UI
                    addMessageToUI(e);
                });
        } catch (error) {
            console.log('Real-time broadcasting not available:', error.message);
            // Fallback: poll for new messages every 30 seconds
            setInterval(loadMessages, 30000);
        }
    } else {
        console.log('Echo not available, using polling mode');
        // Fallback: poll for new messages every 30 seconds
        setInterval(loadMessages, 30000);
    }
});
</script>
@endpush