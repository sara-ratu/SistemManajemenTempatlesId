<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\HonorTutor;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayrollService
{
    // Persentase split billing
    const TUTOR_SHARE    = 0.90; // 90% untuk tutor
    const PLATFORM_SHARE = 0.10; // 10% untuk platform

    /**
     * Hitung split billing dari total pembayaran booking
     * Dipanggil saat booking selesai (status = completed)
     */
    public function hitungHonor(Booking $booking): array
    {
        $totalBayar    = $booking->total_harga ?? $booking->pembayaran?->jumlah ?? 0;
        $honorTutor    = (int) round($totalBayar * self::TUTOR_SHARE);
        $feePlatform   = (int) round($totalBayar * self::PLATFORM_SHARE);

        return [
            'total_bayar'  => $totalBayar,
            'honor_tutor'  => $honorTutor,
            'fee_platform' => $feePlatform,
        ];
    }

    /**
     * Proses pembayaran honor tutor setelah sesi selesai
     * Dipanggil otomatis saat status booking diubah ke 'selesai'
     */
    public function prosesHonor(Booking $booking): HonorTutor
    {
        // Cek apakah honor sudah pernah diproses
        $existing = HonorTutor::where('booking_id', $booking->id)->first();
        if ($existing) {
            return $existing;
        }

        $kalkulasi = $this->hitungHonor($booking);

        DB::beginTransaction();
        try {
            $honor = HonorTutor::create([
                'tutor_id'     => $booking->tutor_id,
                'booking_id'   => $booking->id,
                'jumlah_honor' => $kalkulasi['honor_tutor'],
                'fee_platform' => $kalkulasi['fee_platform'],
                'total_bayar'  => $kalkulasi['total_bayar'],
                'status'       => 'pending',   // pending → diproses → dibayar
                'periode'      => Carbon::now()->format('Y-m'),
                'keterangan'   => 'Honor sesi ' . $booking->tanggal_sesi,
            ]);

            DB::commit();

            Log::info('Honor tutor diproses', [
                'booking_id'   => $booking->id,
                'tutor_id'     => $booking->tutor_id,
                'honor_tutor'  => $kalkulasi['honor_tutor'],
                'fee_platform' => $kalkulasi['fee_platform'],
            ]);

            return $honor;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal proses honor tutor: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Tandai honor sebagai sudah dibayarkan ke tutor
     */
    public function bayarHonor(HonorTutor $honor, string $metodePembayaran = 'transfer'): HonorTutor
    {
        $honor->update([
            'status'            => 'dibayar',
            'tanggal_dibayar'   => Carbon::now(),
            'metode_pembayaran' => $metodePembayaran,
        ]);

        return $honor->fresh();
    }

    /**
     * Batch pembayaran honor — bayar semua honor pending untuk periode tertentu
     */
    public function batchBayarHonor(string $periode, string $metodePembayaran = 'transfer'): array
    {
        $honors = HonorTutor::where('status', 'pending')
            ->where('periode', $periode)
            ->get();

        $berhasil = 0;
        $gagal    = 0;

        foreach ($honors as $honor) {
            try {
                $this->bayarHonor($honor, $metodePembayaran);
                $berhasil++;
            } catch (\Exception $e) {
                Log::error('Gagal bayar honor ID ' . $honor->id . ': ' . $e->getMessage());
                $gagal++;
            }
        }

        return [
            'total'    => $honors->count(),
            'berhasil' => $berhasil,
            'gagal'    => $gagal,
            'periode'  => $periode,
        ];
    }

    /**
     * Rekap honor tutor per bulan
     */
    public function rekapBulanan(int $tutorId, string $periode): array
    {
        $honors = HonorTutor::where('tutor_id', $tutorId)
            ->where('periode', $periode)
            ->get();

        return [
            'tutor_id'        => $tutorId,
            'periode'         => $periode,
            'total_sesi'      => $honors->count(),
            'total_honor'     => $honors->sum('jumlah_honor'),
            'total_fee'       => $honors->sum('fee_platform'),
            'sudah_dibayar'   => $honors->where('status', 'dibayar')->sum('jumlah_honor'),
            'belum_dibayar'   => $honors->where('status', 'pending')->sum('jumlah_honor'),
            'detail'          => $honors,
        ];
    }

    /**
     * Summary pendapatan tutor (all-time)
     */
    public function summaryPendapatan(int $tutorId): array
    {
        $honors = HonorTutor::where('tutor_id', $tutorId)->get();

        $bulanIni = Carbon::now()->format('Y-m');
        $bulanLalu = Carbon::now()->subMonth()->format('Y-m');

        return [
            'total_pendapatan'     => $honors->where('status', 'dibayar')->sum('jumlah_honor'),
            'pending_dibayar'      => $honors->where('status', 'pending')->sum('jumlah_honor'),
            'pendapatan_bulan_ini' => $honors->where('periode', $bulanIni)->where('status', 'dibayar')->sum('jumlah_honor'),
            'pendapatan_bulan_lalu'=> $honors->where('periode', $bulanLalu)->where('status', 'dibayar')->sum('jumlah_honor'),
            'total_sesi_selesai'   => $honors->count(),
        ];
    }

    /**
     * Format rupiah
     */
    public static function formatRupiah(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
