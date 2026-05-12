<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Booking;
use App\Models\MatchingLog;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard admin
    public function dashboard()
    {
        $stats = [
            'total_murid'  => User::where('role','murid')->count(),
            'total_tutor'  => User::where('role','tutor')->count(),
            'pending'      => TutorProfile::where('status_verifikasi','pending')->count(),
            'total_booking'=> Booking::count(),
        ];

        $tutorPending = TutorProfile::with('user')
            ->where('status_verifikasi','pending')
            ->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'tutorPending'));
    }

    // Daftar semua tutor
    public function daftarTutor(Request $request)
    {
        $status  = $request->get('status', 'all');
        $query   = TutorProfile::with('user');

        if ($status !== 'all') {
            $query->where('status_verifikasi', $status);
        }

        $tutors = $query->latest()->paginate(15);
        return view('admin.tutor-list', compact('tutors', 'status'));
    }

    // Verifikasi tutor
    public function verifikasiTutor(TutorProfile $profile, string $aksi)
    {
        $profile->update([
            'status_verifikasi' => $aksi === 'approve' ? 'verified' : 'rejected'
        ]);

        return back()->with('success',
            'Tutor ' . ($aksi === 'approve' ? 'diverifikasi' : 'ditolak') . '!'
        );
    }

    // Log matching
    public function matchingLog()
    {
        $logs = MatchingLog::with(['murid','tutor'])
            ->latest()->paginate(20);

        return view('admin.matching-log', compact('logs'));
    }
}
