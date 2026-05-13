{{-- resources/views/chat/room.blade.php --}}
@extends('layouts.app')

@section('title', 'Chat dengan ' . $lawan->name)

@push('head')
    @vite(['resources/js/echo.js'])
@endpush

@section('content')
<div class="flex flex-col h-[calc(100vh-64px)] max-w-2xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center gap-3 px-4 py-3 bg-white border-b border-gray-200 shadow-sm">
        <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-gray-800 mr-1">
            ←
        </a>
        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-700">
            {{ mb_substr($lawan->name, 0, 1) }}
        </div>
        <div>
            <p class="font-semibold text-gray-800 leading-tight">{{ $lawan->name }}</p>
            <p class="text-xs text-gray-400">{{ ucfirst($lawan->role) }}</p>
        </div>
        @if ($room->status === 'closed')
            <span class="ml-auto text-xs bg-gray-100 text-gray-500 px-2 py-1 rounded-full">Chat Ditutup</span>
        @endif
    </div>

    {{-- Pesan --}}
    <div id="chat-messages" class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-gray-50">
        @foreach ($messages as $msg)
            @include('chat._bubble', ['msg' => $msg, 'myId' => auth()->id()])
        @endforeach
        <div id="typing-indicator" class="hidden text-xs text-gray-400 italic px-2">
            {{ $lawan->name }} sedang mengetik...
        </div>
    </div>

    {{-- Input --}}
    @if ($room->isActive())
    <div class="bg-white border-t border-gray-200 px-4 py-3">
        <form id="chat-form" class="flex items-end gap-2" enctype="multipart/form-data">
            @csrf
            {{-- Upload file --}}
            <label class="cursor-pointer text-gray-400 hover:text-blue-600 flex-shrink-0 p-2">
                📎
                <input type="file" id="file-input" class="hidden"
                       accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip">
            </label>

            {{-- Text --}}
            <textarea id="msg-input" name="isi" rows="1"
                      placeholder="Tulis pesan..."
                      class="flex-1 border border-gray-300 rounded-xl px-3 py-2 text-sm resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 max-h-32 overflow-y-auto"
                      style="min-height:40px"></textarea>

            {{-- Kirim --}}
            <button type="submit"
                    class="flex-shrink-0 bg-blue-600 hover:bg-blue-700 text-white rounded-xl px-4 py-2 text-sm font-medium transition-colors">
                Kirim
            </button>
        </form>

        {{-- Preview file terpilih --}}
        <div id="file-preview" class="hidden mt-2 text-xs text-gray-500 flex items-center gap-2">
            <span id="file-name"></span>
            <button onclick="clearFile()" class="text-red-400 hover:text-red-600">✕</button>
        </div>
    </div>
    @else
        <div class="text-center py-3 text-sm text-gray-400 bg-white border-t border-gray-200">
            Room ini sudah ditutup. Tidak bisa mengirim pesan baru.
        </div>
    @endif
</div>

{{-- Template bubble untuk JS --}}
<template id="bubble-tpl">
    <div class="flex justify-end">
        <div class="max-w-xs lg:max-w-md bg-blue-600 text-white rounded-2xl rounded-tr-sm px-4 py-2 text-sm shadow-sm">
            <span class="bubble-isi"></span>
            <div class="text-right text-xs text-blue-200 mt-1 bubble-time"></div>
        </div>
    </div>
</template>

<script>
const ROOM_ID   = {{ $room->id }};
const MY_ID     = {{ auth()->id() }};
const SEND_URL  = "{{ route('chat.messages.store', $room->id) }}";
const READ_URL  = "{{ route('chat.messages.read', $room->id) }}";
const TYPING_URL= "{{ route('chat.typing', $room->id) }}";
const CSRF      = document.querySelector('meta[name=csrf-token]').content;

// ── Scroll ke bawah saat load ──────────────────────
const msgContainer = document.getElementById('chat-messages');
msgContainer.scrollTop = msgContainer.scrollHeight;

// ── Mark read saat buka ────────────────────────────
fetch(READ_URL, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF } });

// ── Auto resize textarea ───────────────────────────
const textarea = document.getElementById('msg-input');
textarea.addEventListener('input', () => {
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
});

// ── Indikator mengetik ──────────────────────────────
let typingTimer;
textarea.addEventListener('keyup', () => {
    clearTimeout(typingTimer);
    fetch(TYPING_URL, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF } });
    typingTimer = setTimeout(() => {}, 2000);
});

// ── File picker ─────────────────────────────────────
const fileInput  = document.getElementById('file-input');
const filePreview= document.getElementById('file-preview');
const fileName   = document.getElementById('file-name');

fileInput.addEventListener('change', () => {
    if (fileInput.files.length) {
        fileName.textContent = fileInput.files[0].name;
        filePreview.classList.remove('hidden');
    }
});

function clearFile() {
    fileInput.value = '';
    filePreview.classList.add('hidden');
    fileName.textContent = '';
}

// ── Tambah bubble ke DOM ────────────────────────────
function appendBubble(msg, isMine) {
    const wrapper = document.createElement('div');
    wrapper.className = 'flex ' + (isMine ? 'justify-end' : 'justify-start');

    let inner = '';
    if (msg.tipe === 'image') {
        inner = `<img src="${msg.file_url}" class="max-w-[200px] rounded-xl" loading="lazy">`;
    } else if (msg.tipe === 'file') {
        inner = `<a href="${msg.file_url}" target="_blank" class="underline">📎 Unduh file</a>`;
    } else {
        inner = `<span>${msg.isi ?? ''}</span>`;
    }

    const bg   = isMine ? 'bg-blue-600 text-white rounded-tr-sm' : 'bg-white text-gray-800 rounded-tl-sm border border-gray-200';
    const time  = msg.created_at ? new Date(msg.created_at).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}) : '';
    const timeClass = isMine ? 'text-blue-200' : 'text-gray-400';

    wrapper.innerHTML = `
        <div class="max-w-xs lg:max-w-md ${bg} rounded-2xl px-4 py-2 text-sm shadow-sm">
            ${inner}
            <div class="text-right text-xs ${timeClass} mt-1">${time}</div>
        </div>`;

    const typing = document.getElementById('typing-indicator');
    msgContainer.insertBefore(wrapper, typing);
    msgContainer.scrollTop = msgContainer.scrollHeight;
}

// ── Kirim pesan ─────────────────────────────────────
document.getElementById('chat-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const isi  = textarea.value.trim();
    const file = fileInput.files[0];
    if (!isi && !file) return;

    const fd = new FormData();
    fd.append('_token', CSRF);
    if (isi)  fd.append('isi', isi);
    if (file) fd.append('file', file);

    // Optimistic UI
    textarea.value = '';
    textarea.style.height = 'auto';
    clearFile();

    const res  = await fetch(SEND_URL, { method: 'POST', body: fd });
    const data = await res.json();
    appendBubble({ ...data, created_at: new Date().toISOString() }, true);
});

// ── Laravel Echo / Reverb ───────────────────────────
if (typeof Echo !== 'undefined') {
    Echo.private(`chat.${ROOM_ID}`)
        .listen('MessageSent', (e) => {
            if (e.sender_id !== MY_ID) {
                appendBubble(e, false);
                // Mark read
                fetch(READ_URL, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF } });
            }
        })
        .listen('.user.typing', (e) => {
            if (e.user_id !== MY_ID) {
                const ind = document.getElementById('typing-indicator');
                ind.classList.remove('hidden');
                clearTimeout(ind._timer);
                ind._timer = setTimeout(() => ind.classList.add('hidden'), 3000);
            }
        });
}
</script>
@endsection
