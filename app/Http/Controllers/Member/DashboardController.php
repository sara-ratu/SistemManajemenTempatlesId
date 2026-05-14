<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Booking aktif (pending atau confirmed)
        $bookingAktif = Booking::where('member_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        // Total sesi yang sudah confirmed
        $totalSesi = Booking::where('member_id', $user->id)
            ->where('status', 'confirmed')
            ->count();

        // Booking terbaru
        $bookingTerbaru = Booking::where('member_id', $user->id)
            ->with(['tutor', 'tutorProfile']) // tambahkan relasi yang dibutuhkan
            ->latest()
            ->take(5)
            ->get();

        // Jumlah tutor unik yang pernah diajar (Tutor Favorit)
        $tutorFavorit = Booking::where('member_id', $user->id)
            ->where('status', 'confirmed')
            ->distinct('tutor_id')
            ->count('tutor_id');   // cara yang benar

        return view('member.dashboard', compact(
            'bookingAktif',
            'totalSesi',
            'bookingTerbaru',
            'tutorFavorit'
        ));
    }
}
