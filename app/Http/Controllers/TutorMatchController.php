<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Services\TutorMatchService;
use Illuminate\Http\Request;

class TutorMatchController extends Controller
{
    public function __construct(
        protected TutorMatchService $matchService
    ) {}

    // Halaman form pencarian
    public function index()
    {
        $subjects = Subject::where('is_active', true)
            ->orderBy('nama_mapel')
            ->get();

        return view('murid.cari-tutor', compact('subjects'));
    }

    // Proses matching dan tampilkan hasil
    public function search(Request $request)
    {
        $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'budget'     => 'nullable|integer|min:0',
            'hari'       => 'nullable|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam'        => 'nullable|date_format:H:i',
            'latitude'   => 'nullable|numeric',
            'longitude'  => 'nullable|numeric',
        ]);

        $kriteria = $request->only([
            'subject_id', 'budget', 'hari',
            'jam', 'latitude', 'longitude',
        ]);

        $hasil = $this->matchService->match(
            $kriteria,
            auth()->id()
        );

        $subjects = Subject::where('is_active', true)
            ->orderBy('nama_mapel')
            ->get();

        return view('murid.cari-tutor', compact('hasil', 'subjects', 'kriteria'));
    }
}
