<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanSesi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Daftar semua laporan yang masuk (status submitted).
     */
    public function index(Request $request)
    {
        $laporans = LaporanSesi::with(['booking.murid', 'tutor'])
            ->when($request->status, fn($q) => $q->where('status_laporan', $request->status))
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.laporan.index', compact('laporans'));
    }

    /**
     * Detail laporan.
     */
    public function show(LaporanSesi $laporanSesi)
    {
        $laporanSesi->load(['booking.murid', 'tutor']);
        return view('admin.laporan.show', compact('laporanSesi'));
    }

    /**
     * Approve laporan → honor tutor bisa dicairkan.
     */
    public function approve(LaporanSesi $laporanSesi)
    {
        abort_unless($laporanSesi->status_laporan === 'submitted', 422, 'Laporan bukan dalam status submitted.');

        $laporanSesi->update(['status_laporan' => 'approved']);

        return back()->with('success', 'Laporan disetujui. Honor tutor dapat dicairkan.');
    }
}
