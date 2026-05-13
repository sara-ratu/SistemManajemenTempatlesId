<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\TutorArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TutorAreaController extends Controller
{
    /**
     * Tampilkan halaman daftar area mengajar tutor yang login.
     */
    public function index()
    {
        $areas = TutorArea::where('tutor_id', Auth::id())
            ->orderByDesc('is_primary')
            ->orderBy('kota_kabupaten')
            ->get();

        return view('tutor.area-mengajar', compact('areas'));
    }

    /**
     * Simpan area baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'provinsi'        => 'nullable|string|max:100',
            'kota_kabupaten'  => 'required|string|max:100',
            'kecamatan'       => 'nullable|string|max:100',
            'kelurahan'       => 'nullable|string|max:100',
            'radius_km'       => 'nullable|numeric|min:1|max:50',
            'is_primary'      => 'nullable|boolean',
        ]);

        $tutorId = Auth::id();

        // Maksimal 10 area per tutor
        $jumlah = TutorArea::where('tutor_id', $tutorId)->count();
        if ($jumlah >= 10) {
            return back()->with('error', 'Maksimal 10 area mengajar. Hapus area lama terlebih dahulu.');
        }

        // Cek duplikat kecamatan di kota yang sama
        $duplikat = TutorArea::where('tutor_id', $tutorId)
            ->where('kota_kabupaten', $data['kota_kabupaten'])
            ->where('kecamatan', $data['kecamatan'] ?? null)
            ->exists();

        if ($duplikat) {
            return back()->with('error', 'Area tersebut sudah ditambahkan sebelumnya.');
        }

        // Kalau set sebagai primary, reset yang lain dulu
        if (! empty($data['is_primary'])) {
            TutorArea::where('tutor_id', $tutorId)->update(['is_primary' => false]);
        }

        // Area pertama otomatis jadi primary
        if ($jumlah === 0) {
            $data['is_primary'] = true;
        }

        TutorArea::create(array_merge($data, [
            'tutor_id'  => $tutorId,
            'radius_km' => $data['radius_km'] ?? 5,
        ]));

        return back()->with('success', 'Area mengajar berhasil ditambahkan.');
    }

    /**
     * Jadikan area tertentu sebagai primary.
     */
    public function setPrimary(TutorArea $area)
    {
        $this->authorizeArea($area);

        TutorArea::where('tutor_id', Auth::id())->update(['is_primary' => false]);
        $area->update(['is_primary' => true]);

        return back()->with('success', 'Area utama berhasil diubah.');
    }

    /**
     * Hapus area mengajar.
     */
    public function destroy(TutorArea $area)
    {
        $this->authorizeArea($area);

        $wasPrimary = $area->is_primary;
        $area->delete();

        // Kalau yang dihapus adalah primary, otomatis promote yang pertama
        if ($wasPrimary) {
            $next = TutorArea::where('tutor_id', Auth::id())->first();
            $next?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Area mengajar dihapus.');
    }

    // ─── Private helper ──────────────────────────────────────

    private function authorizeArea(TutorArea $area): void
    {
        abort_if($area->tutor_id !== Auth::id(), 403, 'Akses ditolak.');
    }
}
