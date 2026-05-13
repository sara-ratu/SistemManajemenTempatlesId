{{-- resources/views/chat/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Chat')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Pesan</h1>

    @forelse ($rooms as $room)
        @php
            $lawan = auth()->id() === $room->tutor_id ? $room->member : $room->tutor;
            $unread = $room->unreadFor(auth()->id());
            $last   = $room->latestMessage;
        @endphp
        <a href="{{ route('chat.room', $room->booking_id) }}"
           class="flex items-center gap-4 p-4 mb-3 bg-white border border-gray-200 rounded-xl hover:shadow-md transition-shadow">

            {{-- Avatar --}}
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-700 font-bold text-lg">
                {{ mb_substr($lawan->name, 0, 1) }}
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <span class="font-semibold text-gray-800 truncate">{{ $lawan->name }}</span>
                    @if ($last)
                        <span class="text-xs text-gray-400 ml-2 flex-shrink-0">
                            {{ $last->created_at->diffForHumans() }}
                        </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 truncate mt-0.5">
                    @if ($last)
                        @if ($last->tipe === 'image') 📷 Gambar
                        @elseif ($last->tipe === 'file') 📎 File
                        @else {{ $last->isi }}
                        @endif
                    @else
                        <span class="italic">Belum ada pesan</span>
                    @endif
                </p>
            </div>

            {{-- Badge unread --}}
            @if ($unread > 0)
                <span class="bg-blue-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0">
                    {{ $unread > 9 ? '9+' : $unread }}
                </span>
            @endif

            {{-- Status closed --}}
            @if ($room->status === 'closed')
                <span class="text-xs text-gray-400 italic flex-shrink-0">Ditutup</span>
            @endif
        </a>
    @empty
        <div class="text-center py-16 text-gray-400">
            <p class="text-5xl mb-4">💬</p>
            <p class="text-lg font-medium">Belum ada percakapan</p>
            <p class="text-sm mt-1">Chat akan muncul setelah booking dikonfirmasi</p>
        </div>
    @endforelse

</div>
@endsection
