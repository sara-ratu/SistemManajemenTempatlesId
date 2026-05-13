<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HonorTutor;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Daftar semua pembayaran
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $pembayarans = Pembayaran::with(['booking.tutor', 'murid'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        $summary = [
            'pending'  => Pembayaran::pending()->count(),
            'verified' => Pembayaran::verified()->count(),
            'total'    => Pembayaran::verified()->sum('jumlah'),
        ];

        return view('admin.pembayaran.index', compact('pembayarans', 'status', 'summary'));
    }

    /**
     * Detail pembayaran
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['booking.tutor.tutorProfile', 'murid', 'verifiedBy']);

        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    /**
     * Verifikasi pembayaran — otomatis buat honor tutor
     */
    public function verify(Request $request, Pembayaran $pembayaran)
    {
        abort_if($pembayaran->status !== 'pending', 403, 'Pembayaran sudah diproses.');

        $pembayaran->update([
            'status'      => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        // Hitung honor tutor
        $komisiPersen = (float) config('app.komisi_platform', env('PLATFORM_KOMISI_PERSEN', 10));
        $jumlahBruto  = $pembayaran->jumlah;
        $komisiRp     = $jumlahBruto * ($komisiPersen / 100);
        $jumlahHonor  = $jumlahBruto - $komisiRp;

        $tutorProfile = $pembayaran->booking->tutor->tutorProfile;

        HonorTutor::create([
            'pembayaran_id'   => $pembayaran->id,
            'booking_id'      => $pembayaran->booking_id,
            'tutor_id'        => $pembayaran->booking->tutor_id,
            'jumlah_bruto'    => $jumlahBruto,
            'komisi_platform' => $komisiPersen,
            'jumlah_honor'    => $jumlahHonor,
            'rekening_bank'   => $tutorProfile?->rekening_bank,
            'nama_rekening'   => $tutorProfile?->nama_rekening,
            'no_rekening'     => $tutorProfile?->no_rekening,
            'status'          => 'pending',
        ]);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran terverifikasi. Honor tutor sudah masuk antrean transfer.');
    }

    /**
     * Tolak pembayaran
     */
    public function reject(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        abort_if($pembayaran->status !== 'pending', 403);

        $pembayaran->update([
            'status'        => 'rejected',
            'catatan_admin' => $request->catatan_admin,
            'verified_by'   => auth()->id(),
        ]);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran ditolak. Murid akan diberitahu.');
    }
}
