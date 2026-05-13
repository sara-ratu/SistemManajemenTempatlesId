<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HonorTutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HonorController extends Controller
{
    /**
     * Daftar honor tutor (antrean transfer)
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $honors = HonorTutor::with(['tutor', 'booking', 'pembayaran'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->latest()
            ->paginate(20);

        $summary = [
            'pending'    => HonorTutor::pending()->count(),
            'ditransfer' => HonorTutor::ditransfer()->count(),
            'total'      => HonorTutor::pending()->sum('jumlah_honor'),
        ];

        return view('admin.honor.index', compact('honors', 'status', 'summary'));
    }

    /**
     * Rekap honor per tutor
     */
    public function rekap(Request $request)
    {
        $honors = HonorTutor::with('tutor')
            ->selectRaw('tutor_id, SUM(jumlah_honor) as total, COUNT(*) as jumlah_sesi, status')
            ->groupBy('tutor_id', 'status')
            ->get()
            ->groupBy('tutor_id');

        return view('admin.honor.rekap', compact('honors'));
    }

    /**
     * Tandai honor sudah ditransfer + upload bukti
     */
    public function transfer(Request $request, HonorTutor $honor)
    {
        $request->validate([
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'catatan'        => 'nullable|string|max:500',
        ]);

        abort_if($honor->status !== 'pending', 403, 'Honor sudah ditransfer sebelumnya.');

        $path = $request->file('bukti_transfer')
            ->store('honor/bukti', 'public');

        $honor->update([
            'status'         => 'ditransfer',
            'bukti_transfer' => $path,
            'ditransfer_at'  => now(),
            'ditransfer_by'  => auth()->id(),
            'catatan'        => $request->catatan,
        ]);

        return redirect()->route('admin.honor.index')
            ->with('success', 'Honor tutor berhasil ditandai sudah ditransfer.');
    }

    /**
     * Transfer massal (bulk) — tandai semua pending sebagai ditransfer
     */
    public function bulkTransfer(Request $request)
    {
        $request->validate([
            'honor_ids'      => 'required|array',
            'honor_ids.*'    => 'exists:honor_tutors,id',
            'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $path = $request->file('bukti_transfer')
            ->store('honor/bukti-bulk', 'public');

        HonorTutor::whereIn('id', $request->honor_ids)
            ->where('status', 'pending')
            ->update([
                'status'         => 'ditransfer',
                'bukti_transfer' => $path,
                'ditransfer_at'  => now(),
                'ditransfer_by'  => auth()->id(),
            ]);

        return redirect()->route('admin.honor.index')
            ->with('success', count($request->honor_ids) . ' honor berhasil ditandai ditransfer.');
    }
}
