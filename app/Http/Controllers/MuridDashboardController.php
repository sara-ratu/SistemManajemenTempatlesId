<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

/**
 * @property \App\Models\User $user
 */
class MuridDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $bookingAktif = Booking::where('murid_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        $totalSesi = Booking::where('murid_id', $user->id)
            ->where('status', 'confirmed')
            ->count();

        $bookingTerbaru = Booking::where('murid_id', $user->id)
            ->with('tutor')
            ->latest()
            ->take(5)
            ->get();

        // Tambahkan ini kalau ada error $tutorFavorit
        $tutorFavorit = Booking::where('murid_id', $user->id)
            ->where('status', 'confirmed')
            ->distinct('tutor_id')
            ->count();

        return view('murid.dashboard', compact(
            'bookingAktif',
            'totalSesi',
            'bookingTerbaru',
            'tutorFavorit'
        ));
    }
}
