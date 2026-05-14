<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatRoomController extends Controller
{
    /**
     * Buka atau buat room chat berdasarkan booking.
     * Hanya Member (member) atau tutor yang terlibat di booking ini.
     */
    public function show(Booking $booking)
    {
        $user = Auth::user();

        // Pastikan user adalah Member atau tutor dari booking ini
        abort_unless(
            $user->id === $booking->Member_id || $user->id === $booking->tutor_id,
            403
        );

        // Buat room jika belum ada
        $room = ChatRoom::firstOrCreate(
            ['booking_id' => $booking->id],
            [
                'tutor_id'  => $booking->tutor_id,
                'member_id' => $booking->Member_id,
                'status'    => 'active',
            ]
        );

        // Tandai semua pesan sebagai sudah dibaca oleh user ini
        $room->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->each(fn($msg) => $msg->markRead());

        $messages = $room->messages()->with('sender')->get();
        $lawan    = $user->id === $booking->tutor_id
            ? $booking->Member
            : $booking->tutor;

        return view('chat.room', compact('room', 'messages', 'booking', 'lawan'));
    }

    /**
     * Admin/sistem tutup room chat.
     */
    public function close(ChatRoom $chatRoom)
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $chatRoom->update([
            'status'    => 'closed',
            'closed_at' => now(),
        ]);

        return back()->with('success', 'Room chat berhasil ditutup.');
    }

    /**
     * Daftar room chat milik user yang sedang login.
     */
    public function index()
    {
        $user  = Auth::user();
        $rooms = ChatRoom::with(['booking', 'latestMessage'])
            ->where(function ($q) use ($user) {
                $q->where('tutor_id', $user->id)
                  ->orWhere('member_id', $user->id);
            })
            ->orderByDesc('updated_at')
            ->get();

        return view('chat.index', compact('rooms'));
    }
}
