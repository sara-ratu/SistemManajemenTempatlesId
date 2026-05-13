<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    // Verifikasi tutor (existing)
    public function verifikasiTutor(TutorProfile $profile, string $aksi)
    {
        $profile->update([
            'status_verifikasi' => $aksi === 'approve' ? 'verified' : 'rejected'
        ]);

        return back()->with('success',
            'Tutor ' . ($aksi === 'approve' ? 'diverifikasi' : 'ditolak') . '!'
        );
    }

    /**
     * Halaman Verifikasi Utama (Pending Tutor & Member)
     */
    public function verificationIndex()
    {
        $pendingTutors = TutorProfile::with('user')
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->paginate(10);

        $pendingMembers = User::where('role', 'murid')
            ->where('is_verified', false)
            ->latest()
            ->paginate(10);

        return view('admin.verification.index', compact('pendingTutors', 'pendingMembers'));
    }

    /**
     * Detail Tutor untuk Verifikasi (Sara/Salma)
     */
    public function verificationTutorDetail($id)
    {
        $tutor = TutorProfile::with(['user', 'subjects', 'areas', 'schedules'])
                    ->findOrFail($id);

        return view('admin.verification.tutor-detail', compact('tutor'));
    }

    /**
     * Approve Tutor (dari halaman detail)
     */
    public function approveTutor(Request $request, $id)
    {
        $profile = TutorProfile::findOrFail($id);

        $profile->update([
            'status_verifikasi' => 'verified',
            'verified_at'       => now(),
            'verified_by'       => auth()->id()
        ]);

        // Optional: Update User juga
        $profile->user->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);

        return redirect()->route('admin.verification.index')
            ->with('success', 'Tutor berhasil diverifikasi dan profil sudah tayang.');
    }

    /**
     * Reject Tutor + Alasan
     */
    public function rejectTutor(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $profile = TutorProfile::findOrFail($id);

        $profile->update([
            'status_verifikasi' => 'rejected',
            'rejection_reason'  => $request->reason,
            'verified_by'       => auth()->id()
        ]);

        return redirect()->route('admin.verification.index')
            ->with('success', 'Tutor telah ditolak.');
    }

    /**
     * Approve Member
     */
    public function approveMember($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);

        return back()->with('success', 'Member berhasil diverifikasi.');
    }

    // Log matching (existing)
    public function matchingLog()
    {
        $logs = MatchingLog::with(['murid','tutor'])
            ->latest()->paginate(20);

        return view('admin.matching-log', compact('logs'));
    }
}
