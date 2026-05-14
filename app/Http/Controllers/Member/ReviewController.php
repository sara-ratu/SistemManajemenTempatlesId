<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use App\Models\TutorProfile;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Form tulis review (Member)
     */
    public function create(Booking $booking)
    {
        // Pastikan booking milik Member yang login
        abort_if($booking->Member_id !== auth()->id(), 403);

        // Hanya booking selesai
        abort_if($booking->status !== 'selesai', 403, 'Hanya sesi selesai yang bisa direview.');

        // Sudah review?
        if ($booking->review) {
            return redirect()->route('Member.riwayat')
                ->with('info', 'Kamu sudah memberikan ulasan untuk sesi ini.');
        }

        return view('Member.review.create', compact('booking'));
    }

    /**
     * Simpan review (Member)
     */
    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->Member_id !== auth()->id(), 403);
        abort_if($booking->status !== 'selesai', 403);
        abort_if($booking->review()->exists(), 403, 'Review sudah dikirim.');

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'Member_id'   => $booking->Member_id,
            'tutor_id'   => $booking->tutor_id,
            'rating'     => $request->rating,
            'komentar'   => $request->komentar,
            'is_visible' => true,
        ]);

        $this->recalculateRating($booking->tutor_id);

        return redirect()->route('Member.riwayat')
            ->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }

    // ── Private Helpers ───────────────────────────────────

    private function recalculateRating(int $tutorId): void
    {
        $avg   = Review::where('tutor_id', $tutorId)->where('is_visible', true)->avg('rating') ?? 0;
        $count = Review::where('tutor_id', $tutorId)->where('is_visible', true)->count();

        TutorProfile::where('user_id', $tutorId)->update([
            'rating_avg'   => round($avg, 2),
            'rating_count' => $count,
        ]);
    }
}
