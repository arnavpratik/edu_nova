{{-- resources/views/chat.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat with {{ $receiver->name }}</title>
    
    {{-- This section contains the styling for the chat window --}}
    <style>
        body { font-family: system-ui, sans-serif; display: flex; flex-direction: column; height: 100vh; margin: 0; background-color: #f4f4f5; }
        #messages { flex-grow: 1; padding: 10px; overflow-y: auto; display: flex; flex-direction: column; }
        .message { margin-bottom: 10px; padding: 8px 12px; border-radius: 18px; max-width: 75%; line-height: 1.4; word-wrap: break-word; }
        .sent { background-color: #3b82f6; color: white; align-self: flex-end; }
        .received { background-color: #e4e4e7; color: black; align-self: flex-start; }
        #chat-form { display: flex; padding: 10px; background-color: #fff; border-top: 1px solid #ddd; }
        #chat-form input { flex-grow: 1; border: 1px solid #ccc; padding: 10px; border-radius: 20px; outline: none; }
        #chat-form button { margin-left: 10px; border: none; background: #3b82f6; color: white; border-radius: 50%; width: 40px; height: 40px; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
    </style>
    
    {{-- This loads your project's main JavaScript file, which includes Laravel Echo --}}
    @vite(['resources/js/app.js'])
</head>
<body>

    {{-- This div will display all the chat messages --}}
    <div id="messages">
        @foreach($messages as $message)
            <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
                {{ $message->message }}
            </div>
        @endforeach
    </div>

    {{-- This is the form for typing and sending a new message --}}
    <form id="chat-form">
        <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
        <button type="submit">➤</button>
    </form>

   {{-- Replace the entire old script block with this one --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const receiverId = {{ $receiver->id }};
        const form = document.getElementById('chat-form');
        const input = document.getElementById('message-input');
        const messagesDiv = document.getElementById('messages');
        let lastMessageId = {{ $messages->last()->id ?? 0 }};

        // Helper function to scroll to the latest message
        function scrollToBottom() {
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        // Function to add a new message to the screen
        function appendMessage(message, type = 'received') {
            const msgEl = document.createElement('div');
            msgEl.classList.add('message', type);
            msgEl.textContent = message.message;
            messagesDiv.appendChild(msgEl);
            scrollToBottom();
        }

        // SENDING a message (this part stays similar)
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (input.value.trim() === '') return;

            // Add our own message to the screen immediately
            const sentMessage = { message: input.value };
            appendMessage(sentMessage, 'sent');

            // Send the message to the server
            fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    receiver_id: receiverId,
                    message: input.value
                })
            });

            input.value = '';
        });

        // POLLING for new messages (this replaces the Echo code)
        setInterval(function() {
            fetch(`/chat/${receiverId}/messages?last_message_id=${lastMessageId}`)
                .then(response => response.json())
                .then(newMessages => {
                    if (newMessages.length > 0) {
                        newMessages.forEach(message => {
                            appendMessage(message, 'received');
                            lastMessageId = message.id; // Update the last message ID
                        });
                    }
                });
        }, 2000); // Check for new messages every 2 seconds
    });
</script>
</body>
</html>