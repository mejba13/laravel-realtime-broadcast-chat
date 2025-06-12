import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.currentUser = window.currentUser || 'John';

window.appendMessage = function ({ message, sender }) {
    const messagesDiv = document.getElementById('messages');
    const isMe = sender === window.currentUser;
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
};

// Optional: Toast Notification Handler
window.showToast = function (message) {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toast-message');
    toastMsg.innerText = message;
    toast.classList.remove('hidden');
    toast.classList.add('flex');
    setTimeout(() => {
        toast.classList.remove('flex');
        toast.classList.add('hidden');
    }, 1800);
};

window.Echo.channel('chat-channel')
    .listen('.message.sent', (e) => {
        window.appendMessage({
            message: e.message,
            sender: e.sender || 'Harry'
        });
        if ((e.sender || 'Harry') !== window.currentUser) {
            window.showToast && window.showToast("New message received via Pusher!");
        }
    });
