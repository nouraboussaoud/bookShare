<!-- Chatbot Widget -->
<div id="chatbot-widget" class="chatbot-widget">
    <!-- Chat Button -->
    <button id="chatbot-toggle" class="chatbot-toggle" aria-label="Ouvrir le chat">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
        </svg>
        <span class="chatbot-badge" id="chatbot-badge" style="display: none;">1</span>
    </button>

    <!-- Chat Window -->
    <div id="chatbot-window" class="chatbot-window" style="display: none;">
        <!-- Header -->
        <div class="chatbot-header">
            <div class="chatbot-header-content">
                <div class="chatbot-avatar">🤖</div>
                <div>
                    <h4 class="chatbot-title">Assistant BookShare</h4>
                    <p class="chatbot-status">
                        <span class="status-dot"></span>
                        En ligne
                    </p>
                </div>
            </div>
            <div class="chatbot-header-actions">
                <button id="chatbot-clear" class="chatbot-action-btn" title="Effacer l'historique">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
                <button id="chatbot-close" class="chatbot-action-btn" title="Fermer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages Container -->
        <div id="chatbot-messages" class="chatbot-messages">
            <!-- Welcome Message -->
            <div class="chatbot-message assistant">
                <div class="message-avatar">🤖</div>
                <div class="message-content">
                    <div class="message-bubble">
                        Bonjour! 👋 Je suis votre assistant BookShare. Je peux vous aider à trouver des livres, répondre à vos questions et vous donner des recommandations. Comment puis-je vous aider aujourd'hui?
                    </div>
                    <div class="message-time">{{ now()->format('H:i') }}</div>
                </div>
            </div>

            <!-- Suggested Questions -->
            <div id="chatbot-suggestions" class="chatbot-suggestions">
                <p class="suggestions-title">Questions suggérées:</p>
                <div class="suggestions-list"></div>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div id="chatbot-typing" class="chatbot-typing" style="display: none;">
            <div class="chatbot-message assistant">
                <div class="message-avatar">🤖</div>
                <div class="message-content">
                    <div class="typing-indicator">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="chatbot-input-area">
            <form id="chatbot-form">
                <input 
                    type="text" 
                    id="chatbot-input" 
                    class="chatbot-input" 
                    placeholder="Tapez votre message..."
                    autocomplete="off"
                    maxlength="1000"
                >
                <button type="submit" class="chatbot-send-btn" id="chatbot-send">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"></line>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.chatbot-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.chatbot-toggle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    position: relative;
}

.chatbot-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
}

.chatbot-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
}

.chatbot-window {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 380px;
    height: 600px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chatbot-header-content {
    display: flex;
    align-items: center;
    gap: 12px;
}

.chatbot-avatar {
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.chatbot-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.chatbot-status {
    margin: 0;
    font-size: 12px;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 6px;
}

.status-dot {
    width: 8px;
    height: 8px;
    background: #10b981;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.chatbot-header-actions {
    display: flex;
    gap: 8px;
}

.chatbot-action-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.chatbot-action-btn:hover {
    background: rgba(255, 255, 255, 0.3);
}

.chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #f9fafb;
}

.chatbot-message {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.chatbot-message.user {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.chatbot-message.user .message-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.message-content {
    max-width: 70%;
}

.chatbot-message.user .message-content {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.message-bubble {
    background: white;
    padding: 12px 16px;
    border-radius: 16px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    word-wrap: break-word;
}

.chatbot-message.user .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.message-time {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 4px;
    padding: 0 4px;
}

.chatbot-suggestions {
    margin: 16px 0;
}

.suggestions-title {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 8px;
    font-weight: 500;
}

.suggestions-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.suggestion-btn {
    background: white;
    border: 1px solid #e5e7eb;
    padding: 10px 14px;
    border-radius: 12px;
    text-align: left;
    cursor: pointer;
    font-size: 13px;
    color: #374151;
    transition: all 0.2s;
}

.suggestion-btn:hover {
    background: #f3f4f6;
    border-color: #667eea;
    color: #667eea;
}

.chatbot-typing {
    padding: 0 16px;
}

.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 12px 16px;
    background: white;
    border-radius: 16px;
    width: fit-content;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #9ca3af;
    border-radius: 50%;
    animation: typing 1.4s infinite;
}

.typing-indicator span:nth-child(2) {
    animation-delay: 0.2s;
}

.typing-indicator span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes typing {
    0%, 60%, 100% { transform: translateY(0); }
    30% { transform: translateY(-10px); }
}

.chatbot-input-area {
    padding: 16px;
    background: white;
    border-top: 1px solid #e5e7eb;
}

#chatbot-form {
    display: flex;
    gap: 8px;
}

.chatbot-input {
    flex: 1;
    padding: 12px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 24px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
}

.chatbot-input:focus {
    border-color: #667eea;
}

.chatbot-send-btn {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.chatbot-send-btn:hover {
    transform: scale(1.1);
}

.chatbot-send-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

@media (max-width: 480px) {
    .chatbot-window {
        width: calc(100vw - 40px);
        height: calc(100vh - 120px);
        bottom: 90px;
        right: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotWindow = document.getElementById('chatbot-window');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotClear = document.getElementById('chatbot-clear');
    const chatbotForm = document.getElementById('chatbot-form');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotTyping = document.getElementById('chatbot-typing');
    const suggestionsContainer = document.querySelector('.suggestions-list');

    let sessionId = localStorage.getItem('chatbot_session_id') || null;
    let isOpen = false;

    // Toggle chat window
    chatbotToggle.addEventListener('click', function() {
        isOpen = !isOpen;
        chatbotWindow.style.display = isOpen ? 'flex' : 'none';
        if (isOpen) {
            chatbotInput.focus();
            loadSuggestions();
        }
    });

    chatbotClose.addEventListener('click', function() {
        isOpen = false;
        chatbotWindow.style.display = 'none';
    });

    // Load suggested questions
    function loadSuggestions() {
        if (suggestionsContainer.children.length > 0) return;

        fetch('/api/chatbot/suggestions', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                data.suggestions.forEach(suggestion => {
                    const btn = document.createElement('button');
                    btn.className = 'suggestion-btn';
                    btn.textContent = suggestion;
                    btn.addEventListener('click', () => {
                        chatbotInput.value = suggestion;
                        sendMessage();
                    });
                    suggestionsContainer.appendChild(btn);
                });
            }
        });
    }

    // Send message
    chatbotForm.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    function sendMessage() {
        const message = chatbotInput.value.trim();
        if (!message) return;

        // Add user message to chat
        addMessage(message, 'user');
        chatbotInput.value = '';

        // Hide suggestions after first message
        const suggestionsDiv = document.getElementById('chatbot-suggestions');
        if (suggestionsDiv) {
            suggestionsDiv.style.display = 'none';
        }

        // Show typing indicator
        chatbotTyping.style.display = 'block';
        scrollToBottom();

        // Send to API
        fetch('/api/chatbot/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: message,
                session_id: sessionId
            })
        })
        .then(response => response.json())
        .then(data => {
            chatbotTyping.style.display = 'none';
            
            if (data.success) {
                sessionId = data.session_id;
                localStorage.setItem('chatbot_session_id', sessionId);
                addMessage(data.message, 'assistant');
            } else {
                addMessage('Désolé, une erreur est survenue. Veuillez réessayer.', 'assistant');
            }
        })
        .catch(error => {
            chatbotTyping.style.display = 'none';
            addMessage('Erreur de connexion. Veuillez vérifier votre connexion internet.', 'assistant');
            console.error('Error:', error);
        });
    }

    function addMessage(text, role) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `chatbot-message ${role}`;
        
        const now = new Date();
        const time = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        
        messageDiv.innerHTML = `
            <div class="message-avatar">${role === 'user' ? '👤' : '🤖'}</div>
            <div class="message-content">
                <div class="message-bubble">${text}</div>
                <div class="message-time">${time}</div>
            </div>
        `;
        
        chatbotMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    function scrollToBottom() {
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    // Clear history
    chatbotClear.addEventListener('click', function() {
        if (confirm('Voulez-vous vraiment effacer l\'historique de conversation?')) {
            fetch('/api/chatbot/history', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    session_id: sessionId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear messages except welcome
                    const messages = chatbotMessages.querySelectorAll('.chatbot-message');
                    messages.forEach((msg, index) => {
                        if (index > 0) msg.remove();
                    });
                    // Show suggestions again
                    document.getElementById('chatbot-suggestions').style.display = 'block';
                }
            });
        }
    });
});
</script>
