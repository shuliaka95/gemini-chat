<!DOCTYPE html>
<html>
<head>
    <title>Gemini Chat</title>
    <style>
        body { font-family: sans-serif; }
        #chat-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        #chat-messages { height: 500px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 8px; }
        .message { margin-bottom: 15px; padding: 10px; border-radius: 8px; }
        .user { background-color: #e6f7ff; margin-left: 20%; }
        .ai { background-color: #f0f0f0; margin-right: 20%; }
        #chat-form { display: flex; gap: 10px; }
        #message-input { flex-grow: 1; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background: #4285f4; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <div id="chat-container">
        <h1>Gemini Chat</h1>
        <div id="chat-messages"></div>
        <form id="chat-form">
            <input type="text" name="message" id="message-input" placeholder="Type your message..." autocomplete="off">
            <button type="submit">Send</button>
        </form>
    </div>

    <script>
        const chatMessages = document.getElementById('chat-messages');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        
        // Load any existing messages from session
        @if(session('chat_history'))
            @foreach(session('chat_history') as $message)
                addMessageToChat('{{ $message["role"] }}', `{!! $message["text"] !!}`);
            @endforeach
        @endif
        
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;
            
            // Add user message to chat
            addMessageToChat('user', message);
            messageInput.value = '';
            
            try {
                const response = await fetch('/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ message })
                });
                
                const data = await response.json();
                addMessageToChat('model', data.response);
            } catch (error) {
                console.error('Error:', error);
                addMessageToChat('model', 'Error: Failed to get response');
            }
        });
        
        function addMessageToChat(role, text) {
            const messageClass = role === 'user' ? 'user' : 'ai';
            const sender = role === 'user' ? 'You' : 'AI';
            
            // Escape HTML but preserve line breaks
            const escapedText = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/\n/g, '<br>');
            
            chatMessages.innerHTML += `
                <div class="message ${messageClass}">
                    <strong>${sender}:</strong> ${escapedText}
                </div>
            `;
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
</body>
</html>