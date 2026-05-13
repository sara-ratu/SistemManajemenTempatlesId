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

    public function index()
    {
        $subjects = Subject::where('is_active', true)
            ->orderBy('nama_mapel')
            ->get();

        return view('murid.cari-tutor', compact('subjects'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'nullable|exists:subjects,id',
            'budget'     => 'nullable|integer|min:0',
            'hari'       => 'nullable|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam'        => 'nullable|date_format:H:i',
            'sesi'       => 'nullable|in:Pagi,Siang,Sore',
            'metode'     => 'nullable|in:online,offline,keduanya',
            'jenjang'    => 'nullable|in:SD,SMP,SMA,Kuliah',
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

        return view('murid.cari-tutor', [
            'hasil'    => $hasil,
            'subjects' => $subjects,
            'kriteria' => $validated,
        ]);
    }
}
