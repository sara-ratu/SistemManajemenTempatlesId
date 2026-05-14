<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Services\TutorMatchService;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    protected TutorMatchService $matchService;

    public function __construct(TutorMatchService $matchService)
    {
        $this->matchService = $matchService;
    }

    /**
     * Tampilkan halaman cari tutor
     */
    public function index()
    {
        $subjects = Subject::where('is_active', true)
            ->orderBy('nama_mapel')
            ->get();

        return view('member.cari-tutor', compact('subjects'));
    }

    /**
     * Proses pencarian / matching tutor
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'budget'     => 'nullable|integer|min:0',
            'hari'       => 'nullable|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam'        => 'nullable|date_format:H:i',
            'sesi'       => 'nullable|in:Pagi,Siang,Sore',
            'metode'     => 'nullable|in:online,offline,both',
            'jenjang'    => 'nullable|in:SD,SMP,SMA,Kuliah,Umum',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
            'kecamatan'  => 'nullable|string|max:100',
            'kota'       => 'nullable|string|max:100',
        ]);

        $hasil = $this->matchService->match(
            $validated,
            auth()->id()
        );

        $subjects = Subject::where('is_active', true)
            ->orderBy('nama_mapel')
            ->get();

        return view('member.cari-tutor', [
            'hasil'     => $hasil,
            'subjects'  => $subjects,
            'kriteria'  => $validated,
            'searching' => true,   // flag untuk menandakan sedang mencari
        ]);
    }
}
