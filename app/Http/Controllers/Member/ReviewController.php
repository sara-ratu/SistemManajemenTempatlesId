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
     * Form tulis review (murid)
     */
    public function create(Booking $booking)
    {
        // Pastikan booking milik murid yang login
        abort_if($booking->murid_id !== auth()->id(), 403);

        // Hanya booking selesai
        abort_if($booking->status !== 'selesai', 403, 'Hanya sesi selesai yang bisa direview.');

        // Sudah review?
        if ($booking->review) {
            return redirect()->route('murid.riwayat')
                ->with('info', 'Kamu sudah memberikan ulasan untuk sesi ini.');
        }

        return view('murid.review.create', compact('booking'));
    }

    /**
     * Simpan review (murid)
     */
    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->murid_id !== auth()->id(), 403);
        abort_if($booking->status !== 'selesai', 403);
        abort_if($booking->review()->exists(), 403, 'Review sudah dikirim.');

        $request->validate([
            'rating'   => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'murid_id'   => $booking->murid_id,
            'tutor_id'   => $booking->tutor_id,
            'rating'     => $request->rating,
            'komentar'   => $request->komentar,
            'is_visible' => true,
        ]);

        $this->recalculateRating($booking->tutor_id);

        return redirect()->route('murid.riwayat')
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
