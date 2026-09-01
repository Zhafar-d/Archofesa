@extends('layouts.owner')

@section('title', 'Chat Admin · Pemilik ARCHOFESA')

@section('content')
<div class="p-6 lg:p-8">
    <div class="mx-auto max-w-6xl">
        {{-- Header Page --}}
        <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">Chat Internal Pemilik & Admin</h1>
                <p class="mt-1 text-sm text-slate-500">Ruang komunikasi realtime terenkripsi antara Pemilik Kost dan Administrator.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Firebase Live
                </span>
            </div>
        </div>

        {{-- Chat Box Main Container --}}
        <div class="flex flex-col h-[650px] overflow-hidden rounded-[32px] border border-[#e7e2d8] bg-white shadow-sm">
            {{-- Chat Topbar --}}
            <div class="flex items-center justify-between border-b border-slate-100 bg-[#faf8f5] px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-[#c9a227] text-sm font-bold text-white shadow-sm">
                        AD
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Administrator ARCHOFESA</h2>
                        <p class="text-xs text-slate-500">Pengelola Operasional & Verifikasi Kost</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold uppercase tracking-wider text-[#c9a227]">Role: Pemilik Kos</span>
                </div>
            </div>

            {{-- Message List (Scrollable) --}}
            <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 bg-slate-50/50 space-y-4">
                <div class="flex justify-center">
                    <span class="rounded-full bg-slate-200/60 px-3 py-1 text-[11px] font-medium text-slate-600">Memuat riwayat percakapan...</span>
                </div>
            </div>

            {{-- Input Bar (Bottom) --}}
            <div class="border-t border-slate-100 bg-white p-4 sm:p-5">
                <form id="messageForm" class="flex items-center gap-3">
                    <input id="messageInput" 
                           type="text" 
                           placeholder="Ketik pesan untuk Admin..." 
                           class="flex-1 rounded-2xl border border-[#e7e2d8] bg-[#faf8f5] px-5 py-3 text-sm text-slate-900 placeholder-slate-400 focus:border-[#c9a227] focus:bg-white focus:outline-none focus:ring-1 focus:ring-[#c9a227]" 
                           autocomplete="off"
                           required>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-2xl bg-[#c9a227] px-6 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-[#b68d1f] active:scale-95">
                        <span>Kirim</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
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
        bubble.className = `max-w-[80%] sm:max-w-[70%] rounded-3xl p-4 shadow-sm ${isMine ? 'bg-[#c9a227] text-white rounded-br-none' : 'bg-white text-slate-900 border border-[#e7e2d8] rounded-bl-none'}`;

        const header = document.createElement('div');
        header.className = `mb-1.5 flex items-center justify-between gap-3 text-[11px] ${isMine ? 'text-white/80' : 'text-slate-500'}`;
        header.innerHTML = `<span class="font-bold">${data.senderName} (${data.senderRole})</span><span>${formatTimestamp(data.timestamp)}</span>`;

        const text = document.createElement('p');
        text.className = 'text-sm leading-relaxed whitespace-pre-wrap';
        text.textContent = data.message;

        bubble.appendChild(header);
        bubble.appendChild(text);

        if (data.actionUrl) {
            const actionBtn = document.createElement('a');
            actionBtn.href = data.actionUrl;
            actionBtn.target = '_blank';
            actionBtn.className = `mt-3 inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-xs font-bold ${isMine ? 'bg-white text-[#c9a227]' : 'bg-[#c9a227] text-white'}`;
            actionBtn.textContent = (data.actionLabel || 'Lihat Detail') + ' →';
            bubble.appendChild(actionBtn);
        }

        messageWrapper.appendChild(bubble);
        messagesContainer.appendChild(messageWrapper);
    };

    onSnapshot(messagesQuery, (snapshot) => {
        messagesContainer.innerHTML = '';
        if (snapshot.empty) {
            messagesContainer.innerHTML = `
                <div class="flex h-full flex-col items-center justify-center text-center p-8">
                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Belum ada percakapan</p>
                    <p class="text-xs text-slate-400 mt-1">Kirim pesan pertama Anda untuk memulai komunikasi dengan Admin.</p>
                </div>
            `;
            return;
        }
        snapshot.forEach((doc) => {
            renderMessage(doc.data());
        });
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    });

    messageForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const text = messageInput.value.trim();
        if (!text) return;

        messageInput.value = '';

        try {
            await addDoc(messagesRef, {
                senderId: userId,
                senderRole: userRole,
                senderName: userName,
                message: text,
                timestamp: serverTimestamp(),
            });
        } catch (e) {
            alert('Gagal mengirim pesan: ' + e.message);
        }
    });
</script>
@endpush
