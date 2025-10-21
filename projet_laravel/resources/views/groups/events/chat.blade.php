@extends('layouts.app')

@section('title', 'Chat - ' . $event->title)

@push('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        overflow: hidden;
    }

    .chat-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: #f5f7fb;
        display: flex;
        flex-direction: column;
        z-index: 9999;
    }

    /* Header */
    .chat-top-bar {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        flex-shrink: 0;
    }

    .chat-top-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .back-button {
        background: rgba(255,255,255,0.2);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
        text-decoration: none;
    }

    .back-button:hover {
        background: rgba(255,255,255,0.3);
        transform: translateX(-2px);
        color: white;
    }

    .chat-title-area {
        flex: 1;
        min-width: 0;
    }

    .chat-title-area h1 {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 2px 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .chat-subtitle {
        font-size: 12px;
        opacity: 0.9;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Messages Area */
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 16px 20px 100px 20px;
        background: #f5f7fb;
    }

    .messages-container::-webkit-scrollbar {
        width: 6px;
    }

    .messages-container::-webkit-scrollbar-track {
        background: transparent;
    }

    .messages-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .message-item {
        display: flex;
        gap: 10px;
        margin-bottom: 16px;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-item.own {
        flex-direction: row-reverse;
    }

    .msg-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .message-item.own .msg-avatar {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .msg-content {
        max-width: 65%;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .msg-sender {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        padding: 0 8px;
    }

    .message-item.own .msg-sender {
        text-align: right;
    }

    .msg-bubble {
        background: white;
        padding: 10px 14px;
        border-radius: 16px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        word-wrap: break-word;
        position: relative;
        cursor: pointer;
        transition: transform 0.2s;
    }

    .msg-bubble:hover {
        transform: scale(1.01);
    }

    .message-item.own .msg-bubble {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }

    .msg-text {
        font-size: 13px;
        line-height: 1.5;
        margin: 0;
    }

    .msg-time {
        font-size: 10px;
        color: #94a3b8;
        padding: 0 8px;
    }

    .message-item.own .msg-time {
        text-align: right;
        color: #94a3b8;
    }

    /* AI Eyes Icon */
    .ai-eyes-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        background: linear-gradient(135deg, #ff6b6b, #4ecdc4);
        border: 2px solid #333;
        border-radius: 50%;
        margin-left: 4px;
        font-size: 12px;
        color: white;
        font-weight: bold;
        animation: aiPulse 2s infinite;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .message-item.own .ai-eyes-icon {
        margin-left: 0;
        margin-right: 4px;
    }

    @keyframes aiPulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.1); }
    }

    .reply-info {
        background: rgba(0,0,0,0.05);
        border-left: 3px solid #667eea;
        padding: 6px 10px;
        border-radius: 8px;
        margin-bottom: 6px;
        font-size: 11px;
    }

    .message-item.own .reply-info {
        background: rgba(255,255,255,0.2);
        border-left-color: white;
    }

    .reply-username {
        font-weight: 600;
        margin-bottom: 2px;
    }

    .reply-message {
        opacity: 0.8;
        font-style: italic;
    }

    /* Input Area */
    .input-area {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        padding: 12px 20px;
        border-top: 1px solid #e2e8f0;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        z-index: 10;
    }

    .reply-preview-box {
        background: #f1f5f9;
        padding: 8px 12px;
        border-radius: 8px;
        margin-bottom: 8px;
        display: none;
        align-items: center;
        justify-content: space-between;
    }

    .reply-preview-box.show {
        display: flex;
    }

    .reply-preview-text {
        flex: 1;
        min-width: 0;
    }

    .reply-preview-label {
        font-size: 11px;
        font-weight: 600;
        color: #667eea;
        margin-bottom: 2px;
    }

    .reply-preview-msg {
        font-size: 12px;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cancel-reply {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .cancel-reply:hover {
        background: #e2e8f0;
        color: #64748b;
    }

    .input-box {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 12px;
        transition: all 0.2s;
    }

    .input-box:focus-within {
        border-color: #667eea;
        background: white;
    }

    .message-textarea {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        font-size: 13px;
        line-height: 1.5;
        resize: none;
        max-height: 120px;
        font-family: inherit;
        padding: 4px 0;
    }

    .message-textarea::placeholder {
        color: #94a3b8;
    }

    .send-btn {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .send-btn:hover:not(:disabled) {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .send-btn i {
        font-size: 14px;
    }

    /* Empty State */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #94a3b8;
        text-align: center;
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.3;
    }

    .empty-state h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 4px;
        color: #64748b;
    }

    .empty-state p {
        font-size: 13px;
        margin: 0;
    }

    /* Typing Indicator */
    .typing-box {
        display: none;
        padding: 8px 20px;
        font-size: 12px;
        color: #64748b;
        font-style: italic;
    }

    .typing-box.show {
        display: block;
    }

    .typing-dots {
        display: inline-flex;
        gap: 3px;
        margin-left: 6px;
    }

    .typing-dot {
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: #94a3b8;
        animation: bounce 1.4s infinite;
    }

    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes bounce {
        0%, 60%, 100% { transform: translateY(0); }
        30% { transform: translateY(-6px); }
    }

    /* Toast Notifications */
    .toast-container {
        position: fixed;
        top: 60px;
        right: 20px;
        z-index: 10000;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .toast-item {
        background: white;
        padding: 12px 16px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 280px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }

    .toast-icon.success { background: #10b981; color: white; }
    .toast-icon.error { background: #ef4444; color: white; }
    .toast-icon.warning { background: #f59e0b; color: white; }

    .toast-text {
        flex: 1;
        font-size: 13px;
        color: #1e293b;
    }

    /* Loading */
    .loading-overlay {
        display: none;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .loading-overlay.show {
        display: flex;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #e2e8f0;
        border-top-color: #667eea;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .chat-top-bar {
            padding: 10px 16px;
        }

        .messages-container {
            padding: 12px 16px 100px 16px;
        }

        .msg-content {
            max-width: 75%;
        }

        .msg-text {
            font-size: 14px;
        }

        .input-area {
            padding: 10px 16px;
        }

        .toast-container {
            right: 16px;
            left: 16px;
        }

        .toast-item {
            min-width: auto;
        }
    }
</style>
@endpush

@section('content')
<div class="chat-wrapper">
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Top Bar -->
    <div class="chat-top-bar">
        <div class="chat-top-left">
            <a href="{{ route('reading-groups.events.show', [$event->readingGroup, $event]) }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="chat-title-area">
                <h1>{{ $event->title }}</h1>
                <div class="chat-subtitle">
                    <i class="fas fa-calendar" style="font-size: 10px;"></i>
                    {{ $event->event_date->format('d/m/Y') }}
                    @if($event->event_time)
                        • {{ $event->event_time->format('H:i') }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="messages-container" id="messagesContainer">
        <div class="loading-overlay show" id="loadingOverlay">
            <div class="spinner"></div>
        </div>
        <div class="empty-state" id="emptyState" style="display: none;">
            <i class="fas fa-comments"></i>
            <h3>Aucun message</h3>
            <p>Commencez la conversation !</p>
        </div>
    </div>

    <!-- Typing Indicator -->
    <div class="typing-box" id="typingBox">
        <span id="typingUserName"></span> est en train d'écrire
        <div class="typing-dots">
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
            <div class="typing-dot"></div>
        </div>
    </div>

    <!-- Input Area -->
    <div class="input-area">
        <div class="reply-preview-box" id="replyPreview">
            <div class="reply-preview-text">
                <div class="reply-preview-label">Réponse à <span id="replyToUser"></span></div>
                <div class="reply-preview-msg" id="replyToMsg"></div>
            </div>
            <button class="cancel-reply" onclick="cancelReply()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="input-box">
            <textarea 
                class="message-textarea" 
                id="messageInput" 
                placeholder="Tapez votre message..."
                rows="1"
                maxlength="1000"></textarea>
            <button class="send-btn" id="sendBtn" disabled>
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventId = {{ $event->id }};
    const messagesContainer = document.getElementById('messagesContainer');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const emptyState = document.getElementById('emptyState');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const typingBox = document.getElementById('typingBox');
    const typingUserName = document.getElementById('typingUserName');
    const toastContainer = document.getElementById('toastContainer');
    const replyPreview = document.getElementById('replyPreview');
    const replyToUser = document.getElementById('replyToUser');
    const replyToMsg = document.getElementById('replyToMsg');

    let currentUser = {
        id: {{ auth()->id() }},
        name: '{{ auth()->user()->name }}',
        avatar: '{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}'
    };
    let replyToData = null;
    let isTyping = false;
    let typingTimeout;
    let existingMessageIds = new Set(); // Track existing messages

    // Initialize
    loadMessages();
    setupInput();
    setInterval(loadMessages, 3000);
    setInterval(checkTyping, 2000);

    function setupInput() {
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            sendBtn.disabled = this.value.trim().length === 0;
            handleTyping();
        });

        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        sendBtn.addEventListener('click', sendMessage);
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
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ typing })
        }).catch(() => {});
    }

    function checkTyping() {
        fetch(`{{ route("events.chat.typing-status", ":event") }}`.replace(':event', eventId))
            .then(r => r.json())
            .then(data => {
                if (data.typing && data.user_name !== currentUser.name) {
                    typingUserName.textContent = data.user_name;
                    typingBox.classList.add('show');
                } else {
                    typingBox.classList.remove('show');
                }
            })
            .catch(() => typingBox.classList.remove('show'));
    }

    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        let data = { message };
        if (replyToData) {
            data.reply_to = replyToData.id;
            data.reply_message = replyToData.message;
            data.reply_user = replyToData.user;
        }

        messageInput.value = '';
        messageInput.style.height = 'auto';
        sendBtn.disabled = true;
        cancelReply();

        fetch(`{{ route("events.chat.messages", ":event") }}`.replace(':event', eventId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                loadMessages();
                if (data.flagged) {
                    showToast('warning', 'Message signalé pour modération');
                }
            } else {
                showToast('error', data.message || 'Erreur d\'envoi');
            }
        })
        .catch(() => showToast('error', 'Erreur de connexion'));
    }

    function loadMessages() {
        fetch(`{{ route("events.chat.messages", ":event") }}`.replace(':event', eventId))
            .then(r => r.json())
            .then(data => {
                loadingOverlay.classList.remove('show');
                if (data.success) {
                    displayMessages(data.messages);
                }
            })
            .catch(() => {
                loadingOverlay.classList.remove('show');
                showToast('error', 'Erreur de chargement');
            });
    }

    function displayMessages(messages) {
        if (messages.length === 0) {
            messagesContainer.innerHTML = '';
            emptyState.style.display = 'flex';
            messagesContainer.appendChild(emptyState);
            existingMessageIds.clear();
            return;
        }

        emptyState.style.display = 'none';
        
        // Get new message IDs from the fetched messages
        const newMessageIds = new Set(messages.map(m => m.id));
        
        // Check if there are any new messages
        const hasNewMessages = messages.some(m => !existingMessageIds.has(m.id));
        
        if (hasNewMessages) {
            // Only rebuild if there are new messages
            const wasAtBottom = isScrolledToBottom();
            
            messagesContainer.innerHTML = '';

            messages.forEach(msg => {
                const isNewMessage = !existingMessageIds.has(msg.id);
                messagesContainer.appendChild(createMessage(msg, isNewMessage));
            });

            // Update the set of existing message IDs
            existingMessageIds = newMessageIds;
            
            // Only scroll to bottom if user was already at bottom or it's their own message
            if (wasAtBottom || messages[messages.length - 1]?.is_own) {
                scrollToBottom();
            }
        }
    }

    function createMessage(msg, animate = false) {
        const div = document.createElement('div');
        div.className = `message-item ${msg.is_own ? 'own' : ''}`;
        div.dataset.messageId = msg.id;
        
        // Only add animation class to new messages
        if (!animate) {
            div.style.animation = 'none';
        }
        
        const replyHtml = msg.reply_to ? `
            <div class="reply-info">
                <div class="reply-username">${escapeHtml(msg.reply_to.user)}</div>
                <div class="reply-message">${escapeHtml(msg.reply_to.message.substring(0, 60))}${msg.reply_to.message.length > 60 ? '...' : ''}</div>
            </div>
        ` : '';

        div.innerHTML = `
            <div class="msg-avatar">${msg.user.avatar}</div>
            <div class="msg-content">
                ${!msg.is_own ? `<div class="msg-sender">${escapeHtml(msg.user.name)}</div>` : ''}
                <div class="msg-bubble" onclick="replyTo('${msg.id}', '${escapeHtml(msg.user.name)}', \`${escapeHtml(msg.message)}\`)">
                    ${replyHtml}
                    <p class="msg-text">${escapeHtml(msg.message)}</p>
                </div>
                <div class="msg-time">${msg.created_at}${msg.ai_used ? '<span class="ai-eyes-icon" title="AI Moderated">👁️</span>' : ''}</div>
            </div>
        `;
        return div;
    }

    function isScrolledToBottom() {
        const threshold = 100; // pixels from bottom
        return messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight < threshold;
    }

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function showToast(type, message) {
        const toast = document.createElement('div');
        toast.className = 'toast-item';
        toast.innerHTML = `
            <div class="toast-icon ${type}">
                <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'exclamation'}"></i>
            </div>
            <div class="toast-text">${message}</div>
        `;
        toastContainer.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    window.replyTo = function(id, user, message) {
        replyToData = { id, user, message };
        replyToUser.textContent = user;
        replyToMsg.textContent = message.substring(0, 50) + (message.length > 50 ? '...' : '');
        replyPreview.classList.add('show');
        messageInput.focus();
    };

    window.cancelReply = function() {
        replyToData = null;
        replyPreview.classList.remove('show');
    };

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    messageInput.focus();
});
</script>
@endpush