<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\TutorProfile;

class ReviewModerationController extends Controller
{
    /**
     * Daftar semua review
     */
    public function index()
    {
        $reviews = Review::with(['Member', 'tutor', 'booking'])
            ->latest()
            ->paginate(20);

        return view('admin.review.index', compact('reviews'));
    }

    /**
     * Toggle visibility (sembunyikan / tampilkan)
     */
    public function toggle(Review $review)
    {
        $review->update(['is_visible' => ! $review->is_visible]);

        $this->recalculateRating($review->tutor_id);

        $status = $review->is_visible ? 'ditampilkan' : 'disembunyikan';

        return back()->with('success', "Review berhasil {$status}.");
    }

    /**
     * Hapus review permanen
     */
    public function destroy(Review $review)
    {
        $tutorId = $review->tutor_id;
        $review->delete();

        $this->recalculateRating($tutorId);

        return back()->with('success', 'Review dihapus.');
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
