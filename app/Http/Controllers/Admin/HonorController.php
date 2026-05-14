<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HonorTutor;
use App\Http\Requests\Admin\HonorTransferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HonorController extends Controller
{
    /**
     * Display a listing of honor tutors.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $query = HonorTutor::with(['tutor'])
            ->latest();

        // Filter berdasarkan status
        if ($status === 'pending') {
            $query->where('status', 'pending');
        } elseif ($status === 'ditransfer') {
            $query->where('status', 'ditransfer');
        }
        // 'all' = tampilkan semua

        $honors = $query->paginate(15)->withQueryString();

        // Summary untuk cards
        $summary = [
            'pending'    => HonorTutor::where('status', 'pending')->count(),
            'ditransfer' => HonorTutor::where('status', 'ditransfer')->count(),
            'total'      => HonorTutor::where('status', 'pending')->sum('jumlah_honor'),
        ];

        return view('admin.honor.index', compact('honors', 'status', 'summary'));
    }

    /**
     * Proses transfer honor ke tutor
     */
    public function transfer(HonorTransferRequest $request, HonorTutor $honor)
    {
        // Cek agar tidak double transfer
        if ($honor->status === 'ditransfer') {
            return redirect()
                ->route('admin.honor.index')
                ->with('error', 'Honor ini sudah ditransfer sebelumnya.');
        }

        DB::beginTransaction();

        try {
            $filePath = $request->file('bukti_transfer')
                               ->store('bukti_transfer/honor', 'public');

            $honor->update([
                'status'           => 'ditransfer',
                'bukti_transfer'   => $filePath,
                'catatan'          => $request->catatan,
                'tanggal_transfer' => now(),
                'transfer_by'      => auth()->id(),
            ]);

            DB::commit();

            return redirect()
                ->route('admin.honor.index')
                ->with('success', "Honor untuk {$honor->tutor->name} berhasil ditransfer.");

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat memproses transfer. Silakan coba lagi.');
        }
    }

    // Method tambahan yang mungkin dibutuhkan di masa depan
    // public function dashboard() { ... }
    // public function gajiTutor() { ... }
    // public function rekap() { ... }
}
