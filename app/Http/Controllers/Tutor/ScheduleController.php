<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    // ──────────────────────────────────────────────
    // Halaman kelola jadwal
    // ──────────────────────────────────────────────

    public function index()
    {
        $profile   = Auth::user()->tutorProfile;
        $schedules = $profile
            ? $profile->schedules()->orderByRaw(
                "FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')"
              )->get()
            : collect();

        // Booking masuk yang perlu dikonfirmasi
        $bookingPending = Booking::where('tutor_id', Auth::id())
            ->where('status', 'pending')
            ->with(['Member', 'subject'])
            ->latest()
            ->get();

        return view('tutor.jadwal', compact('schedules', 'profile', 'bookingPending'));
    }

    // ──────────────────────────────────────────────
    // Simpan jadwal baru
    // ──────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'hari'        => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $profile = Auth::user()->tutorProfile;

        if (! $profile) {
            return back()->with('error', 'Lengkapi profil dulu sebelum mengatur jadwal.');
        }

        // Cek duplikat hari + jam yang sama
        $duplikat = $profile->schedules()
            ->where('hari', $request->hari)
            ->where('jam_mulai', $request->jam_mulai)
            ->exists();

        if ($duplikat) {
            return back()->with('error', 'Jadwal pada hari dan jam tersebut sudah ada.');
        }

        Schedule::create([
            'tutor_profile_id' => $profile->id,
            'hari'             => $request->hari,
            'jam_mulai'        => $request->jam_mulai,
            'jam_selesai'      => $request->jam_selesai,
            'is_available'     => true,
        ]);

        return back()->with('success', 'Jadwal berhasil ditambahkan!');
    }

    // ──────────────────────────────────────────────
    // Hapus jadwal
    // ──────────────────────────────────────────────

    public function destroy(Schedule $schedule)
    {
        abort_if(
            $schedule->tutorProfile->user_id !== Auth::id(),
            403,
            'Akses ditolak.'
        );

        $schedule->delete();

        return back()->with('success', 'Jadwal dihapus.');
    }

    // ──────────────────────────────────────────────
    // Konfirmasi booking masuk (tutor approve)
    // ──────────────────────────────────────────────

    public function konfirmasi(Booking $booking)
    {
        $this->authorizeBooking($booking);

        abort_if($booking->status !== 'pending', 422, 'Booking sudah diproses.');

        $booking->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
            'confirmed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Booking dikonfirmasi. Member akan diberitahu.');
    }

    // ──────────────────────────────────────────────
    // Tolak booking masuk (tutor reject)
    // ──────────────────────────────────────────────

    public function tolak(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        abort_if($booking->status !== 'pending', 422, 'Booking sudah diproses.');

        $request->validate([
            'alasan' => 'required|string|max:500',
        ]);

        $booking->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->alasan,
            'confirmed_by'     => Auth::id(),
        ]);

        return back()->with('success', 'Booking ditolak.');
    }

    // ──────────────────────────────────────────────
    // Tandai sesi selesai (tutor)
    // ──────────────────────────────────────────────

    public function selesai(Booking $booking)
    {
        $this->authorizeBooking($booking);

        abort_if($booking->status !== 'confirmed', 422, 'Hanya booking terkonfirmasi yang bisa diselesaikan.');

        $booking->update([
            'status'     => 'selesai',
            'selesai_at' => now(),
        ]);

        return back()->with('success', 'Sesi ditandai selesai.');
    }

    // ──────────────────────────────────────────────
    // Helper: pastikan booking milik tutor ini
    // ──────────────────────────────────────────────

    private function authorizeBooking(Booking $booking): void
    {
        abort_if($booking->tutor_id !== Auth::id(), 403, 'Akses ditolak.');
    }
}
