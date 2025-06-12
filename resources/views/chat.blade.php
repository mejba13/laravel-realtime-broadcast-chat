<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Chat | Harry & John</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-2xl">

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-6 right-6 z-50 hidden">
        <div class="flex items-center bg-green-600 text-white px-4 py-3 rounded-lg shadow-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span id="toast-message" class="font-semibold"></span>
        </div>
    </div>

    <!-- Header -->
    <div class="py-6 bg-white shadow mb-6 rounded-2xl">
        <h1 class="text-2xl md:text-3xl font-extrabold text-center text-blue-700 tracking-tight">
            Laravel Broadcasting Real-Time Chat Demo
        </h1>
        <p class="text-center text-gray-500 mt-1 text-sm">
            Supported drivers:
            <span class="font-medium text-green-600">Pusher</span>,
            <span class="font-medium text-indigo-600">Reverb</span>,
            <span class="font-medium text-pink-600">Ably</span>,
            <span class="font-medium text-yellow-600">Redis</span>,
            <span class="font-medium text-gray-700">Log</span>,
            <span class="font-medium text-gray-400">Null</span>
        </p>
    </div>
    <!-- Chat Card -->
    <div class="bg-white rounded-2xl shadow-xl flex flex-col">
        <!-- Chat Header -->
        <div class="flex items-center justify-between p-6 border-b">
            <div class="flex items-center space-x-3">
                <img src="https://ui-avatars.com/api/?name=John" alt="John" class="w-10 h-10 rounded-full border">
                <div>
                    <p class="font-bold text-lg">John <span class="text-xs text-gray-500">(You)</span></p>
                    <p class="text-sm text-green-500">Online</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <img src="https://ui-avatars.com/api/?name=Harry" alt="Harry" class="w-10 h-10 rounded-full border">
                <div>
                    <p class="font-bold text-lg text-blue-800">Harry <span class="text-xs text-gray-500">(Bot)</span></p>
                    <p class="text-sm text-gray-400">Human Agent</p>
                </div>
            </div>
        </div>
        <!-- Chat Section -->
        <div id="messages" class="flex-1 overflow-y-auto p-6 space-y-3 bg-gray-50"></div>
        <!-- Input -->
        <form id="chat-form" class="flex gap-2 p-6 border-t bg-white">
            <input type="text" id="message-input" placeholder="Type your message..." autocomplete="off"
                   class="flex-1 border rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition" />
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-semibold hover:bg-blue-700 transition">Send</button>
        </form>
    </div>
</div>

<script>
    const currentUser = 'John'; // Or set dynamically for multi-user demo

    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toast-message');
        toastMsg.innerText = message;
        toast.classList.remove('hidden');
        toast.classList.add('flex');
        setTimeout(() => {
            toast.classList.remove('flex');
            toast.classList.add('hidden');
        }, 1800);
    }

    function appendMessage({ message, sender }) {
        const messagesDiv = document.getElementById('messages');
        const isMe = sender === currentUser;

        const wrapper = document.createElement('div');
        wrapper.className = 'flex items-end space-x-2 ' + (isMe ? 'justify-end' : 'justify-start');

        // Avatar
        const avatar = document.createElement('img');
        avatar.className = 'w-8 h-8 rounded-full border shadow';
        avatar.src = isMe
            ? "https://ui-avatars.com/api/?name=John"
            : "https://ui-avatars.com/api/?name=Harry";

        // Bubble
        const bubble = document.createElement('div');
        bubble.className = 'px-4 py-2 rounded-xl shadow max-w-xs break-words ' +
            (isMe
                ? 'bg-blue-600 text-white ml-2'
                : 'bg-gray-200 text-gray-900 mr-2');
        bubble.innerText = message;

        if (isMe) {
            wrapper.appendChild(bubble);
            wrapper.appendChild(avatar);
        } else {
            wrapper.appendChild(avatar);
            wrapper.appendChild(bubble);
        }

        messagesDiv.appendChild(wrapper);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        let message = document.getElementById('message-input').value;
        if (!message.trim()) return;

        // Show instantly on your side
        appendMessage({ message: message, sender: currentUser });

        axios.post('/send', { message: message, sender: currentUser })
            .then(res => {
                document.getElementById('message-input').value = '';
            });
    });


</script>

</body>
</html>
