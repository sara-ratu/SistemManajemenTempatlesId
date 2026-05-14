<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\LaporanSesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanSesiController extends Controller
{
    /**
     * Form isi laporan sesi untuk booking tertentu.
     */
    public function create(Booking $booking)
    {
        abort_unless($booking->tutor_id === Auth::id(), 403);
        abort_unless($booking->status === 'selesai', 403, 'Booking belum selesai.');

        // Cek sudah ada laporan atau belum
        $laporan = LaporanSesi::where('booking_id', $booking->id)->first();

        return view('tutor.laporan.create', compact('booking', 'laporan'));
    }

    /**
     * Simpan laporan (draft atau langsung submit).
     */
    public function store(Request $request, Booking $booking)
    {
        abort_unless($booking->tutor_id === Auth::id(), 403);

        $data = $request->validate([
            'tanggal_sesi'       => 'required|date',
            'materi_diajarkan'   => 'required|string|max:2000',
            'perkembangan_Member' => 'nullable|string|max:2000',
            'kendala'            => 'nullable|string|max:1000',
            'catatan_tambahan'   => 'nullable|string|max:1000',
            'aksi'               => 'required|in:draft,submit',
        ]);

        $status = $data['aksi'] === 'submit' ? 'submitted' : 'draft';

        LaporanSesi::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'tutor_id'           => Auth::id(),
                'tanggal_sesi'       => $data['tanggal_sesi'],
                'materi_diajarkan'   => $data['materi_diajarkan'],
                'perkembangan_Member' => $data['perkembangan_Member'],
                'kendala'            => $data['kendala'],
                'catatan_tambahan'   => $data['catatan_tambahan'],
                'status_laporan'     => $status,
            ]
        );

        $msg = $status === 'submitted'
            ? 'Laporan berhasil dikirim.'
            : 'Laporan disimpan sebagai draft.';

        return redirect()->route('tutor.laporan.index')->with('success', $msg);
    }

    /**
     * Daftar semua laporan milik tutor yang login.
     */
    public function index()
    {
        $laporans = LaporanSesi::with('booking.Member')
            ->where('tutor_id', Auth::id())
            ->orderByDesc('tanggal_sesi')
            ->paginate(15);

        return view('tutor.laporan.index', compact('laporans'));
    }

    /**
     * Detail laporan.
     */
    public function show(LaporanSesi $laporanSesi)
    {
        abort_unless($laporanSesi->tutor_id === Auth::id(), 403);

        return view('tutor.laporan.show', compact('laporanSesi'));
    }
}
