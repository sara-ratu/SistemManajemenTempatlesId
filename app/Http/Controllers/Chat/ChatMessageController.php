<?php

namespace App\Http\Controllers\Chat;

use App\Events\MessageSent;
use App\Events\UserTyping;
use App\Http\Controllers\Controller;
use App\Models\ChatRoom;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatMessageController extends Controller
{
    /**
     * Kirim pesan baru ke room chat.
     */
    public function store(Request $request, ChatRoom $chatRoom)
    {
        $user = Auth::user();

        // Hanya peserta room yang boleh kirim
        abort_unless(
            $user->id === $chatRoom->tutor_id || $user->id === $chatRoom->member_id,
            403
        );
        abort_if($chatRoom->status === 'closed', 403, 'Room chat sudah ditutup.');

        $request->validate([
            'isi'  => 'nullable|string|max:2000',
            'file' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,zip',
        ]);

        $tipe     = 'text';
        $filePath = null;

        if ($request->hasFile('file')) {
            $file     = $request->file('file');
            $tipe     = str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'file';
            $filePath = $file->store('chat_attachments', 'public');
        }

        $message = ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'sender_id'    => $user->id,
            'tipe'         => $tipe,
            'isi'          => $request->isi,
            'file_path'    => $filePath,
            'is_read'      => false,
        ]);

        $message->load('sender');

        // Broadcast ke channel private
        broadcast(new MessageSent($message))->toOthers();

        // Update timestamp room agar muncul di urutan atas
        $chatRoom->touch();

        if ($request->wantsJson()) {
            return response()->json([
                'id'         => $message->id,
                'tipe'       => $message->tipe,
                'isi'        => $message->isi,
                'file_url'   => $message->fileUrl(),
                'created_at' => $message->created_at->format('H:i'),
            ]);
        }

        return back();
    }

    /**
     * Tandai pesan sebagai sudah dibaca (dipanggil via AJAX).
     */
    public function markRead(Request $request, ChatRoom $chatRoom)
    {
        $user = Auth::user();

        abort_unless(
            $user->id === $chatRoom->tutor_id || $user->id === $chatRoom->member_id,
            403
        );

        $chatRoom->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->each(fn($msg) => $msg->markRead());

        return response()->json(['ok' => true]);
    }

    /**
     * Broadcast indikator mengetik.
     */
    public function typing(ChatRoom $chatRoom)
    {
        $user = Auth::user();

        abort_unless(
            $user->id === $chatRoom->tutor_id || $user->id === $chatRoom->member_id,
            403
        );

        broadcast(new UserTyping($chatRoom->id, $user->id, $user->name))->toOthers();

        return response()->json(['ok' => true]);
    }
}
