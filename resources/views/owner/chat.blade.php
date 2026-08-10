@extends('layouts.dashboard-app')

@section('title', 'Owner Chat · ARCHOFESA KOST')

@section('content')
<div class="mx-auto max-w-6xl px-6 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900">Chat Owner & Admin</h1>
        <p class="mt-2 text-slate-600">Realtime chat menggunakan Firebase Firestore antara Owner dan Admin.</p>
    </div>

    <div class="flex h-[calc(100vh-150px)] flex-col overflow-hidden rounded-[32px] border border-[#e7e2d8] bg-white shadow-sm">
        <div class="border-b border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-900">Percakapan</h2>
            <p class="mt-1 text-sm text-slate-600">Pesan terbaru ditampilkan secara langsung di sini.</p>
        </div>

        <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 bg-slate-50 space-y-4"></div>

        <div class="border-t border-slate-200 p-6">
            <form id="messageForm" class="flex gap-3">
                <input id="messageInput" type="text" placeholder="Ketik pesan..." class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-3 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" autocomplete="off">
                <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">Kirim</button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script type="module">
    const firebaseConfig = @json($firebaseConfig);

    import { initializeApp } from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js';
    import { getFirestore, collection, doc, addDoc, query, orderBy, onSnapshot, serverTimestamp } from 'https://www.gstatic.com/firebasejs/9.22.0/firebase-firestore.js';

    const app = initializeApp(firebaseConfig);
    const db = getFirestore(app);
    const conversationId = @json($conversationId);
    const userId = @json($userId);
    const userRole = @json($userRole);
    const userName = @json($userName);
    const messagesContainer = document.getElementById('messagesContainer');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');

    const chatDocRef = doc(collection(db, 'chats'), conversationId);
    const messagesRef = collection(chatDocRef, 'messages');
    const messagesQuery = query(messagesRef, orderBy('timestamp'));

    const formatTimestamp = (timestamp) => {
        if (!timestamp) return '';
        const date = timestamp.toDate();
        return new Intl.DateTimeFormat('id-ID', { hour: '2-digit', minute: '2-digit', day: '2-digit', month: 'short' }).format(date);
    };

    const renderMessage = (data) => {
        const isMine = data.senderId === userId;
        const messageWrapper = document.createElement('div');
        messageWrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;

        const bubble = document.createElement('div');
        bubble.className = `max-w-[80%] rounded-3xl p-4 shadow-sm ${isMine ? 'bg-blue-600 text-white' : 'bg-white text-slate-900 border border-slate-200'}`;

        const header = document.createElement('div');
        header.className = 'mb-2 flex items-center justify-between gap-2 text-xs text-slate-500';
        header.innerHTML = `<span>${data.senderName} · ${data.senderRole}</span><span>${formatTimestamp(data.timestamp)}</span>`;

        const text = document.createElement('p');
        text.className = 'text-sm leading-relaxed';
        text.textContent = data.message;

        bubble.appendChild(header);
        bubble.appendChild(text);

        if (data.actionUrl) {
            const actionBtn = document.createElement('a');
            actionBtn.href = data.actionUrl;
            actionBtn.target = '_blank';
            actionBtn.className = `mt-3 inline-flex rounded-full px-4 py-2 text-xs font-semibold ${isMine ? 'bg-white text-blue-700' : 'bg-[#e2e8f0] text-slate-900'}`;
            actionBtn.textContent = data.actionLabel || 'Buka tautan';
            bubble.appendChild(actionBtn);
        }

        messageWrapper.appendChild(bubble);
        messagesContainer.appendChild(messageWrapper);
    };

    onSnapshot(messagesQuery, (snapshot) => {
        messagesContainer.innerHTML = '';
        snapshot.forEach((doc) => {
            renderMessage(doc.data());
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });

    messageForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!messageInput.value.trim()) return;

        await addDoc(messagesRef, {
            senderId: userId,
            senderRole: userRole,
            senderName: userName,
            message: messageInput.value.trim(),
            timestamp: serverTimestamp(),
        });

        messageInput.value = '';
    });
</script>
@endpush
