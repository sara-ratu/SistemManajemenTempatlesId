<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TutorProfile;
use App\Models\TutorSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // ──────────────────────────────────────────────
    // Form booking (murid pilih jadwal tutor)
    // ──────────────────────────────────────────────

    public function create(User $tutor)
    {
        $profile   = TutorProfile::where('user_id', $tutor->id)->firstOrFail();
        $jadwal    = TutorSchedule::where('tutor_id', $tutor->id)->orderBy('hari')->get();
        $subjects  = $profile->subjects()->get();

        return view('murid.booking.create', compact('tutor', 'profile', 'jadwal', 'subjects'));
    }

    // ──────────────────────────────────────────────
    // Simpan booking baru
    // ──────────────────────────────────────────────

    public function store(Request $request, User $tutor)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'tanggal'    => 'required|date|after_or_equal:today',
            'jam_mulai'  => 'required|date_format:H:i',
            'jam_selesai'=> 'required|date_format:H:i|after:jam_mulai',
            'catatan'    => 'nullable|string|max:500',
        ]);

        $profile = TutorProfile::where('user_id', $tutor->id)->firstOrFail();

        // Hitung durasi dalam jam (minimal 1 jam)
        [$hMulai, $mMulai]     = explode(':', $request->jam_mulai);
        [$hSelesai, $mSelesai] = explode(':', $request->jam_selesai);
        $menitMulai   = (int)$hMulai * 60 + (int)$mMulai;
        $menitSelesai = (int)$hSelesai * 60 + (int)$mSelesai;
        $durasi = max(1, ($menitSelesai - $menitMulai) / 60);

        // Cek bentrok jadwal tutor di tanggal yang sama
        $bentrok = Booking::where('tutor_id', $tutor->id)
            ->where('tanggal', $request->tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai]);
            })
            ->exists();

        if ($bentrok) {
            return back()->with('error', 'Jadwal tersebut sudah dipesan. Pilih waktu lain.')->withInput();
        }

        Booking::create([
            'murid_id'   => Auth::id(),
            'tutor_id'   => $tutor->id,
            'subject_id' => $request->subject_id,
            'tanggal'    => $request->tanggal,
            'jam_mulai'  => $request->jam_mulai,
            'jam_selesai'=> $request->jam_selesai,
            'harga'      => $profile->harga_per_jam * $durasi,
            'status'     => 'pending',
            'catatan'    => $request->catatan,
        ]);

        return redirect()->route('murid.riwayat')
            ->with('success', 'Booking berhasil dikirim! Tunggu konfirmasi dari tutor.');
    }

    // ──────────────────────────────────────────────
    // Riwayat booking murid
    // ──────────────────────────────────────────────

    public function riwayat(Request $request)
    {
        $status = $request->get('status', 'all');

        $bookings = Booking::with(['tutor', 'subject'])
            ->where('murid_id', Auth::id())
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        return view('murid.booking.riwayat', compact('bookings', 'status'));
    }

    // ──────────────────────────────────────────────
    // Batalkan booking (murid)
    // ──────────────────────────────────────────────

    public function cancel(Booking $booking)
    {
        abort_if($booking->murid_id !== Auth::id(), 403);
        abort_if(! in_array($booking->status, ['pending']), 422, 'Booking tidak bisa dibatalkan.');

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
