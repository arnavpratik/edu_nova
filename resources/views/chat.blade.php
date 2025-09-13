<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat with {{ $receiver->name }}</title>
    <style>
        body { font-family: system-ui, sans-serif; display: flex; flex-direction: column; height: 100vh; margin: 0; background-color: #f4f4f5; }
        #messages { flex-grow: 1; padding: 10px; overflow-y: auto; display: flex; flex-direction: column; }
        .message { margin-bottom: 10px; padding: 8px 12px; border-radius: 18px; max-width: 75%; line-height: 1.4; word-wrap: break-word; }
        .message img { max-width: 100%; border-radius: 15px; margin-top: 5px; } /* Style for images */
        .sent { background-color: #3b82f6; color: white; align-self: flex-end; }
        .received { background-color: #e4e4e7; color: black; align-self: flex-start; }
        #chat-form { display: flex; align-items: center; padding: 10px; background-color: #fff; border-top: 1px solid #ddd; }
        #chat-form input[type="text"] { flex-grow: 1; border: 1px solid #ccc; padding: 10px; border-radius: 20px; outline: none; }
        #chat-form button { margin-left: 10px; border: none; background: #3b82f6; color: white; border-radius: 50%; width: 40px; height: 40px; font-size: 1.2rem; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        .file-upload-label { cursor: pointer; padding: 8px; }
        .file-upload-label svg { width: 24px; height: 24px; }
    </style>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div id="messages">
        {{-- This loop displays messages when the page first loads --}}
        @foreach($messages as $message)
            <div class="message {{ $message->sender_id === auth()->id() ? 'sent' : 'received' }}">
                @if ($message->message)
                    {{ $message->message }}
                @endif
                
                {{-- ✅ CORRECTED PART: Check for image_path and create a real <img> tag --}}
                @if ($message->image_path)
                    <img src="{{ asset($message->image_path) }}" alt="Chat Image">
                @endif
            </div>
        @endforeach
    </div>

    <form id="chat-form">
        {{-- ... your form inputs ... --}}
        <label for="image-upload" class="file-upload-label">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.122 2.122l7.81-7.81M15.375 9.13l2.122-2.122a1.5 1.5 0 0 0-2.122-2.122L15.375 9.13Z" /></svg>
        </label>
        <input type="file" id="image-upload" name="image" accept="image/*" hidden>
        <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
        <button type="submit">➤</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const receiverId = {{ $receiver->id }};
            const form = document.getElementById('chat-form');
            const textInput = document.getElementById('message-input');
            const imageInput = document.getElementById('image-upload');
            const messagesDiv = document.getElementById('messages');
            let lastMessageId = {{ $messages->last()->id ?? 0 }};

            function scrollToBottom() { messagesDiv.scrollTop = messagesDiv.scrollHeight; }

            function appendMessage(data, type) {
                const msgEl = document.createElement('div');
                msgEl.classList.add('message', type);
                
                if (data.message) {
                    // Create a text node to prevent HTML injection
                    msgEl.appendChild(document.createTextNode(data.message));
                }
                
                // ✅ CORRECTED PART: Check for image_url and create a real <img> element
                if (data.image_url) {
                    const img = document.createElement('img');
                    img.src = data.image_url;
                    img.alt = "Chat Image";
                    msgEl.appendChild(img);
                }

                messagesDiv.appendChild(msgEl);
                scrollToBottom();
            }

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const messageText = textInput.value;
                const imageFile = imageInput.files[0];

                if (messageText.trim() === '' && !imageFile) return;

                const formData = new FormData();
                formData.append('receiver_id', receiverId);
                formData.append('message', messageText);

                if (imageFile) {
                    formData.append('image', imageFile);
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        appendMessage({ message: messageText, image_url: event.target.result }, 'sent');
                    }
                    reader.readAsDataURL(imageFile);
                } else {
                    appendMessage({ message: messageText, image_url: null }, 'sent');
                }

                fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
body: formData
                });

                textInput.value = '';
                imageInput.value = '';
            });
            
            setInterval(function() {
                fetch(`/chat/${receiverId}/messages?last_message_id=${lastMessageId}`)
                    .then(response => response.json())
                    .then(newMessages => {
                        if (newMessages.length > 0) {
                            newMessages.forEach(message => {
                                appendMessage(message, 'received');
                                lastMessageId = message.id;
                            });
                        }
                    });
            }, 2000);
        });
    </script>
</body>
</html>