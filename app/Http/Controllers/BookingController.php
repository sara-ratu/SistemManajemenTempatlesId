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
        /** @var \App\Models\User $tutor */
        if (!$tutor->isTutor() || !$tutor->tutorProfile) {
            abort(404, 'Tutor tidak ditemukan.');
        }

        $subjects = $tutor->tutorProfile->subjects()->get();

        return view('murid.booking', compact('tutor', 'subjects'));
    }

    /**
     * Simpan booking baru
     */
    public function store(Request $request, User $tutor)
    {
        /** @var \App\Models\User $tutor */
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

        // Cek bentrok jadwal
        $bentrok = Booking::where('tutor_id', $tutor->id)
            ->where('tanggal', $request->tanggal)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                      });
            })
            ->exists();

        if ($bentrok) {
            return back()->withInput()->with('error', 'Jadwal tersebut sudah dipesan.');
        }

        try {
            DB::beginTransaction();

            Booking::create([
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
                ->with('success', 'Booking berhasil dibuat! Menunggu konfirmasi tutor.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem.');
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

        return view('murid.booking', compact('bookings'));
    }
}
