<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TutorProfile;
use App\Models\Booking;
use App\Models\MatchingLog;
use App\Models\HonorTutor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Dashboard Admin
     */
    public function dashboard()
    {
        $stats = [
            'total_member'   => User::where('role', 'member')->count(),
            'total_tutor'    => User::where('role', 'tutor')->count(),
            'pending_tutor'  => TutorProfile::where('status_verifikasi', 'pending')->count(),
            'total_booking'  => Booking::count(),
        ];

        $tutorPending = TutorProfile::with('user')
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'tutorPending'));
    }

    /**
     * Halaman Verifikasi Utama
     */
    public function verificationIndex()
    {
        $pendingTutors = TutorProfile::with('user')
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->paginate(10);

        $pendingMembers = User::where('role', 'member')
            ->where('is_verified', false)
            ->latest()
            ->paginate(10);

        return view('admin.verification.index', compact('pendingTutors', 'pendingMembers'));
    }

    public function verificationTutorDetail($id)
    {
        $tutor = TutorProfile::with(['user', 'subjects', 'areas', 'schedules'])
                    ->findOrFail($id);

        return view('admin.verification.tutor-detail', compact('tutor'));
    }

    public function approveTutor($id)
    {
        $profile = TutorProfile::findOrFail($id);

        $profile->update([
            'status_verifikasi' => 'verified',
            'verified_at'       => now(),
            'verified_by'       => auth()->id()
        ]);

        $profile->user->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);

        return redirect()->route('admin.verification.index')
            ->with('success', 'Tutor berhasil diverifikasi dan siap tayang.');
    }

    public function rejectTutor(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $profile = TutorProfile::findOrFail($id);

        $profile->update([
            'status_verifikasi' => 'rejected',
            'rejection_reason'  => $request->reason,
            'verified_by'       => auth()->id()
        ]);

        return redirect()->route('admin.verification.index')
            ->with('success', 'Tutor telah ditolak.');
    }

    public function approveMember($id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);

        return back()->with('success', 'Member berhasil diverifikasi.');
    }

    public function matchingLog()
    {
        $logs = MatchingLog::with(['member', 'tutor'])   // diperbaiki
            ->latest()
            ->paginate(20);

        return view('admin.matching-log', compact('logs'));
    }

    /**
     * ===================== HONOR TUTOR =====================
     */
    public function honorIndex(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = HonorTutor::with('tutor');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $honors = $query->latest()->paginate(15);

        $summary = [
            'pending'    => HonorTutor::where('status', 'pending')->count(),
            'ditransfer' => HonorTutor::where('status', 'ditransfer')->count(),
            'total'      => HonorTutor::where('status', 'pending')->sum('jumlah_honor'),
        ];

        return view('admin.honor.index', compact('honors', 'summary', 'status'));
    }

    public function honorCreate()
    {
        $tutors = User::where('role', 'tutor')->get();
        return view('admin.honor.create', compact('tutors'));
    }

    public function honorStore(Request $request)
    {
        $request->validate([
            'tutor_id'     => 'required|exists:users,id',
            'jumlah_honor' => 'required|numeric|min:1000',
            'periode'      => 'required|string|max:100',
            'catatan'      => 'nullable|string',
        ]);

        HonorTutor::create([
            'tutor_id'     => $request->tutor_id,
            'jumlah_honor' => $request->jumlah_honor,
            'periode'      => $request->periode,
            'catatan'      => $request->catatan,
            'status'       => 'pending',
        ]);

        return redirect()->route('admin.honor.index')
                         ->with('success', 'Honor tutor berhasil ditambahkan.');
    }

    public function honorEdit(HonorTutor $honor)
    {
        $honor->load('tutor');
        return view('admin.honor.edit', compact('honor'));
    }

    public function honorUpdate(Request $request, HonorTutor $honor)
    {
        $request->validate([
            'jumlah_honor' => 'required|numeric|min:1000',
            'periode'      => 'required|string|max:100',
            'catatan'      => 'nullable|string',
        ]);

        $honor->update($request->only(['jumlah_honor', 'periode', 'catatan']));

        return redirect()->route('admin.honor.index')
                         ->with('success', 'Data honor berhasil diperbarui.');
    }

    public function honorDestroy(HonorTutor $honor)
    {
        $honor->delete();
        return redirect()->route('admin.honor.index')
                         ->with('success', 'Honor berhasil dihapus.');
    }

    public function honorTransfer(Request $request, HonorTutor $honor)
    {
        $request->validate([
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan'        => 'nullable|string|max:255',
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $honor->update([
            'status'           => 'ditransfer',
            'bukti_transfer'   => $path,
            'catatan'          => $request->catatan,
            'tanggal_transfer' => now(),
            'admin_id'         => auth()->id(),
        ]);

        return redirect()->route('admin.honor.index')
                         ->with('success', 'Honor berhasil ditransfer ke tutor.');
    }

    /**
     * ===================== DAFTAR MEMBER =====================
     */
    public function daftarMember()
    {
        $members = User::where('role', 'member')
                    ->withCount('bookingsAsMember')
                    ->latest()
                    ->paginate(15);

        return view('admin.member.index', compact('members'));
    }

    public function memberEdit(User $user)
    {
        if ($user->role !== 'member') {
            abort(403, 'Bukan member.');
        }

        return view('admin.member.edit', compact('user'));
    }

    public function memberUpdate(Request $request, User $user)
    {
        if ($user->role !== 'member') {
            abort(403, 'Bukan member.');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required',
             'email',
             'max:255',
             \Illuminate\Validation\Rule::unique('users')->ignore($user)
             ],
            'no_hp'       => 'nullable|string|max:20',
            'alamat'      => 'nullable|string',
            'kota'        => 'nullable|string|max:100',
            'is_verified' => 'boolean',
        ]);

        $user->update($request->only([
            'name', 'email', 'no_hp', 'alamat', 'kota', 'is_verified'
        ]));

        return redirect()->route('admin.member.index')
                         ->with('success', 'Data member berhasil diperbarui.');
    }

    public function memberToggleStatus(User $user)
    {
        if ($user->role !== 'member') {
            abort(403);
        }

        $newStatus = !$user->is_active;
        $user->update(['is_active' => $newStatus]);

        $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun member berhasil {$statusText}.");
    }
}
