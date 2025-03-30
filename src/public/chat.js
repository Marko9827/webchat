const chatLog = document.getElementById('chat-log');
const messageInput = document.getElementById('message-input');
const sendButton = document.getElementById('send-button');

const socket = new WebSocket('ws://localhost:8080');

socket.onmessage = function (event) {
    chatLog.innerHTML += '<p>' + event.data + '</p>';
};

sendButton.addEventListener('click', function () {
    socket.send(messageInput.value);
    messageInput.value = '';
});