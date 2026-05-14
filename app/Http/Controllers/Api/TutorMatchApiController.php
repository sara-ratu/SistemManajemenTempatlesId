<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TutorMatchService;
use Illuminate\Http\Request;

class TutorMatchApiController extends Controller
{
    public function __construct(
        protected TutorMatchService $matchService
    ) {}

    public function search(Request $request)
    {
        $kriteria = $request->only([
            'subject_id', 'budget', 'hari',
            'jam', 'latitude', 'longitude',
        ]);

        $hasil = $this->matchService->match(
            $kriteria,
            $request->Member_id ?? 0
        );

        return response()->json([
            'status' => 'success',
            'total'  => count($hasil),
            'data'   => $hasil,
        ]);
    }
}
