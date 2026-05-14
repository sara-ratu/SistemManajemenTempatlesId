<?php

namespace App\Http\Controllers\Tutor;

use App\Http\Controllers\Controller;
use App\Models\HonorTutor;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendapatanController extends Controller
{
    public function __construct(protected PayrollService $payrollService) {}

    /**
     * Dashboard pendapatan tutor — riwayat honor dan status pembayaran
     */
    public function index(Request $request)
    {
        $tutor    = Auth::user();
        $periode  = $request->get('periode', Carbon::now()->format('Y-m'));

        // Summary all-time
        $summary = $this->payrollService->summaryPendapatan($tutor->id);

        // Rekap bulan yang dipilih
        $rekap = $this->payrollService->rekapBulanan($tutor->id, $periode);

        // Riwayat honor — paginated
        $riwayat = HonorTutor::where('tutor_id', $tutor->id)
            ->with('booking')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Data chart 6 bulan terakhir
        $chartData = $this->getChartData($tutor->id);

        // Daftar periode (bulan) yang tersedia untuk filter
        $periodeList = HonorTutor::where('tutor_id', $tutor->id)
            ->selectRaw('DISTINCT periode')
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        return view('tutor.pendapatan', compact(
            'summary',
            'rekap',
            'riwayat',
            'chartData',
            'periode',
            'periodeList'
        ));
    }

    /**
     * Detail honor per booking
     */
    public function show(HonorTutor $honorTutor)
    {
        // Pastikan hanya tutor pemilik yang bisa lihat
        abort_if($honorTutor->tutor_id !== Auth::id(), 403);

        $honorTutor->load('booking.Member', 'booking.subject');

        return view('tutor.pendapatan-detail', compact('honorTutor'));
    }

    /**
     * Data chart pendapatan 6 bulan terakhir untuk grafik di view
     */
    private function getChartData(int $tutorId): array
    {
        $labels = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan    = Carbon::now()->subMonths($i);
            $periode  = $bulan->format('Y-m');
            $label    = $bulan->translatedFormat('M Y');

            $total = HonorTutor::where('tutor_id', $tutorId)
                ->where('periode', $periode)
                ->where('status', 'dibayar')
                ->sum('jumlah_honor');

            $labels[] = $label;
            $values[] = $total;
        }

        return [
            'labels' => $labels,
            'values' => $values,
        ];
    }
}
