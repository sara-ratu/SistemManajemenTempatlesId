<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Tampilkan form booking
     */
    public function create(User $tutor)
    {
        // Pastikan yang diakses adalah tutor yang valid
        if (!$tutor->isTutor() || !$tutor->tutorProfile) {
            abort(404, 'Tutor tidak ditemukan atau belum diverifikasi.');
        }

        $subjects = $tutor->tutorProfile->subjects()->get();

        return view('murid.booking', compact('tutor', 'subjects'));
    }

    /**
     * Simpan booking baru
     */
    public function store(Request $request, User $tutor)
    {
        if (!$tutor->isTutor() || !$tutor->tutorProfile) {
            abort(404, 'Tutor tidak ditemukan.');
        }

        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'tanggal'    => 'required|date|after:today',
            'jam_mulai'  => 'required|date_format:H:i',
            'jam_selesai'=> 'required|date_format:H:i|after:jam_mulai',
            'catatan'    => 'nullable|string|max:500',
        ]);

        // Cek apakah jadwal sudah ada (prevent double booking)
        $sudahAda = Booking::where('tutor_id', $tutor->id)
            ->where('tanggal', $request->tanggal)
            ->where(function ($q) use ($request) {
                $q->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                  ->orWhere(function ($q) use ($request) {
                      $q->where('jam_mulai', '<=', $request->jam_mulai)
                        ->where('jam_selesai', '>=', $request->jam_selesai);
                  });
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($sudahAda) {
            return back()->withInput()->with('error', 'Jadwal tersebut sudah dipesan oleh murid lain.');
        }

        try {
            DB::beginTransaction();

            $booking = Booking::create([
                'murid_id'   => auth()->id(),
                'tutor_id'   => $tutor->id,
                'subject_id' => $request->subject_id,
                'tanggal'    => $request->tanggal,
                'jam_mulai'  => $request->jam_mulai,
                'jam_selesai'=> $request->jam_selesai,
                'harga'      => $tutor->tutorProfile->harga_min ?? 0,
                'status'     => 'pending',
                'catatan'    => $request->catatan,
            ]);

            DB::commit();

            return redirect()->route('murid.riwayat')
                ->with('success', 'Booking berhasil dibuat! Menunggu konfirmasi dari tutor.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    /**
     * Riwayat booking murid
     */
    public function riwayat()
    {
        $bookings = Booking::where('murid_id', auth()->id())
            ->with(['tutor', 'subject'])
            ->latest()
            ->paginate(10);

        return view('murid.riwayat', compact('bookings'));
    }
}
