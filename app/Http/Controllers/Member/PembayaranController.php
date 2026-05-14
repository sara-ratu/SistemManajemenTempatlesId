<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Form upload bukti pembayaran
     */
    public function create(Booking $booking)
    {
        abort_if($booking->Member_id !== auth()->id(), 403);
        abort_if($booking->status !== 'confirmed', 403, 'Booking belum dikonfirmasi tutor.');

        // Cek sudah bayar belum
        if ($booking->pembayaran()->exists()) {
            return redirect()->route('Member.riwayat')
                ->with('info', 'Pembayaran sudah dikirim, menunggu verifikasi admin.');
        }

        return view('Member.pembayaran.create', compact('booking'));
    }

    /**
     * Simpan bukti pembayaran
     */
    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->Member_id !== auth()->id(), 403);
        abort_if($booking->status !== 'confirmed', 403);
        abort_if($booking->pembayaran()->exists(), 403, 'Pembayaran sudah dikirim.');

        $request->validate([
            'metode'          => 'required|string|max:50',
            'bukti_transfer'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = $request->file('bukti_transfer')
            ->store('pembayaran/bukti', 'public');

        Pembayaran::create([
            'booking_id'     => $booking->id,
            'Member_id'       => auth()->id(),
            'jumlah'         => $booking->harga,
            'metode'         => $request->metode,
            'bukti_transfer' => $path,
            'status'         => 'pending',
        ]);

        return redirect()->route('Member.riwayat')
            ->with('success', 'Bukti pembayaran berhasil dikirim. Admin akan memverifikasi segera.');
    }

    /**
     * Status pembayaran Member
     */
    public function status(Booking $booking)
    {
        abort_if($booking->Member_id !== auth()->id(), 403);

        $pembayaran = $booking->pembayaran;

        return view('Member.pembayaran.status', compact('booking', 'pembayaran'));
    }
}
