<?php

namespace App\Services;

use App\Models\TutorProfile;
use App\Models\MatchingLog;

class TutorMatchService
{
    // Bobot setiap kriteria (total = 1.0)
    const BOBOT_MAPEL   = 0.30;
    const BOBOT_LOKASI  = 0.25;
    const BOBOT_HARGA   = 0.20;
    const BOBOT_JADWAL  = 0.15;
    const BOBOT_RATING  = 0.10;

    // Jarak maksimal dalam km (di luar ini skor lokasi = 0)
    const JARAK_MAX_KM  = 30;

    public function match(array $kriteria, int $murid_id): array
    {
        // Ambil semua tutor yang sudah verified dan aktif
        $tutors = TutorProfile::with(['user', 'subjects', 'schedules'])
            ->where('status_verifikasi', 'verified')
            ->where('is_active', true)
            ->get();

        $hasil = [];

        foreach ($tutors as $tutor) {
            $skor_mapel  = $this->skorMapel($tutor, $kriteria);
            $skor_lokasi = $this->skorLokasi($tutor, $kriteria);
            $skor_harga  = $this->skorHarga($tutor, $kriteria);
            $skor_jadwal = $this->skorJadwal($tutor, $kriteria);
            $skor_rating = $this->skorRating($tutor);

            // Hitung skor total weighted
            $skor_total =
                ($skor_mapel  * self::BOBOT_MAPEL)  +
                ($skor_lokasi * self::BOBOT_LOKASI) +
                ($skor_harga  * self::BOBOT_HARGA)  +
                ($skor_jadwal * self::BOBOT_JADWAL) +
                ($skor_rating * self::BOBOT_RATING);

            // Hanya tampilkan tutor yang punya mapel sesuai
            if ($skor_mapel == 0) continue;

            // Simpan ke matching_logs
            MatchingLog::create([
                'murid_id'      => $murid_id,
                'tutor_id'      => $tutor->user_id,
                'skor_lokasi'   => round($skor_lokasi, 2),
                'skor_mapel'    => round($skor_mapel, 2),
                'skor_harga'    => round($skor_harga, 2),
                'skor_jadwal'   => round($skor_jadwal, 2),
                'skor_rating'   => round($skor_rating, 2),
                'skor_total'    => round($skor_total, 2),
                'kriteria_input'=> $kriteria,
            ]);

            $hasil[] = [
                'tutor'       => $tutor,
                'skor_total'  => round($skor_total, 2),
                'skor_mapel'  => round($skor_mapel, 2),
                'skor_lokasi' => round($skor_lokasi, 2),
                'skor_harga'  => round($skor_harga, 2),
                'skor_jadwal' => round($skor_jadwal, 2),
                'skor_rating' => round($skor_rating, 2),
                'jarak_km'    => $this->hitungJarak(
                    $kriteria['latitude'] ?? 0,
                    $kriteria['longitude'] ?? 0,
                    $tutor->user->latitude ?? 0,
                    $tutor->user->longitude ?? 0
                ),
            ];
        }

        // Urutkan dari skor tertinggi
        usort($hasil, fn($a, $b) => $b['skor_total'] <=> $a['skor_total']);

        // Kembalikan maksimal 10 tutor terbaik
        return array_slice($hasil, 0, 10);
    }

    // ── Skor Mata Pelajaran ───────────────────
    private function skorMapel(TutorProfile $tutor, array $kriteria): float
    {
        if (empty($kriteria['subject_id'])) return 50.0;

        $punya = $tutor->subjects->pluck('id')->contains($kriteria['subject_id']);
        return $punya ? 100.0 : 0.0;
    }

    // ── Skor Lokasi (Haversine Formula) ──────
    private function skorLokasi(TutorProfile $tutor, array $kriteria): float
    {
        if (empty($kriteria['latitude']) || empty($kriteria['longitude'])) return 50.0;
        if (empty($tutor->user->latitude) || empty($tutor->user->longitude)) return 30.0;

        $jarak = $this->hitungJarak(
            $kriteria['latitude'],
            $kriteria['longitude'],
            $tutor->user->latitude,
            $tutor->user->longitude
        );

        if ($jarak <= 0) return 100.0;
        if ($jarak >= self::JARAK_MAX_KM) return 0.0;

        // Semakin dekat semakin tinggi skornya
        return round((1 - $jarak / self::JARAK_MAX_KM) * 100, 2);
    }

    // ── Skor Harga ────────────────────────────
    private function skorHarga(TutorProfile $tutor, array $kriteria): float
    {
        if (empty($kriteria['budget'])) return 50.0;

        $budget = (int) $kriteria['budget'];

        // Budget murid sesuai atau di atas harga min tutor
        if ($budget >= $tutor->harga_min && $budget <= $tutor->harga_max) {
            return 100.0; // Pas di range harga
        } elseif ($budget >= $tutor->harga_min) {
            return 80.0;  // Budget lebih dari harga, masih oke
        } elseif ($budget >= $tutor->harga_min * 0.8) {
            return 40.0;  // Budget sedikit di bawah, mungkin bisa negosiasi
        }

        return 0.0; // Budget terlalu jauh di bawah harga tutor
    }

    // ── Skor Jadwal ───────────────────────────
    private function skorJadwal(TutorProfile $tutor, array $kriteria): float
    {
        if (empty($kriteria['hari'])) return 50.0;

        $jadwalCocok = $tutor->schedules
            ->where('hari', $kriteria['hari'])
            ->where('is_available', true)
            ->first();

        if (!$jadwalCocok) return 0.0;

        // Cek juga kesesuaian jam jika diisi
        if (!empty($kriteria['jam'])) {
            $jamMinta  = strtotime($kriteria['jam']);
            $jamMulai  = strtotime($jadwalCocok->jam_mulai);
            $jamSelesai= strtotime($jadwalCocok->jam_selesai);

            if ($jamMinta >= $jamMulai && $jamMinta <= $jamSelesai) {
                return 100.0; // Hari dan jam cocok
            }
            return 60.0; // Hari cocok tapi jam kurang pas
        }

        return 100.0; // Hari cocok
    }

    // ── Skor Rating ───────────────────────────
    private function skorRating(TutorProfile $tutor): float
    {
        if ($tutor->total_review == 0) return 50.0; // Tutor baru, skor netral

        // Rating 1-5 dikonversi ke 0-100
        return round(($tutor->rating_rata / 5) * 100, 2);
    }

    // ── Haversine Formula ─────────────────────
    public function hitungJarak(
        float $lat1, float $lon1,
        float $lat2, float $lon2
    ): float {
        if ($lat1 == 0 || $lon1 == 0 || $lat2 == 0 || $lon2 == 0) return 999;

        $R = 6371; // Radius bumi dalam km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return round($R * $c, 2); // Jarak dalam km
    }
}
