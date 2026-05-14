<?php

namespace App\Services;

use App\Models\TutorProfile;
use App\Models\MatchingLog;

class TutorMatchService
{
    // Bobot setiap kriteria (total = 1.0)
    // Catatan: BOBOT_LOKASI hanya aktif saat metode = offline
    const BOBOT_MAPEL   = 0.30;
    const BOBOT_LOKASI  = 0.25;
    const BOBOT_HARGA   = 0.20;
    const BOBOT_JADWAL  = 0.15;
    const BOBOT_RATING  = 0.10;

    // Jarak maksimal dalam km (di luar ini skor lokasi = 0)
    const JARAK_MAX_KM  = 30;

    // Rentang jam untuk pengelompokan sesi
    const SESI_PAGI   = ['mulai' => '06:00', 'selesai' => '11:59'];
    const SESI_SIANG  = ['mulai' => '12:00', 'selesai' => '15:59'];
    const SESI_SORE   = ['mulai' => '16:00', 'selesai' => '20:00'];

    public function match(array $kriteria, int $Member_id): array
    {
        // Ambil semua tutor yang sudah verified dan aktif
        // Sertakan relasi TutorArea untuk filter lokasi offline
        $tutors = TutorProfile::with(['user', 'subjects', 'schedules', 'areas'])
            ->where('status_verifikasi', 'verified')
            ->where('is_active', true)
            ->get();

        $hasil       = [];
        $metode      = $kriteria['metode'] ?? null;   // 'online' | 'offline' | null
        $isOffline   = $metode === 'offline';

        foreach ($tutors as $tutor) {

            // ── HARD FILTER 1: Metode Pembelajaran ──────────────────────────
            // Jika member pilih metode tertentu, tutor yang tidak support → skip
            if (!$this->tutorSupportMetode($tutor, $metode)) {
                continue;
            }

            // ── HARD FILTER 2: Jenjang Pendidikan ───────────────────────────
            // Jika member pilih jenjang, tutor yang tidak mengajar jenjang itu → skip
            if (!$this->tutorSupportJenjang($tutor, $kriteria['jenjang'] ?? null)) {
                continue;
            }

            // ── HARD FILTER 3: Mata Pelajaran ────────────────────────────────
            // Jika member pilih mapel, tutor yang tidak punya mapel itu → skip
            $skor_mapel = $this->skorMapel($tutor, $kriteria);
            if (!empty($kriteria['subject_id']) && $skor_mapel == 0.0) {
                continue;
            }

            // ── HARD FILTER 4: Lokasi Offline ────────────────────────────────
            // Jika mode offline, tutor tanpa data area/koordinat → skip
            $skor_lokasi = $this->skorLokasi($tutor, $kriteria, $isOffline);
            if ($isOffline && $skor_lokasi === 0.0) {
                continue;
            }

            // ── Hitung skor komponen lainnya ─────────────────────────────────
            $skor_harga  = $this->skorHarga($tutor, $kriteria);
            $skor_jadwal = $this->skorJadwal($tutor, $kriteria);
            $skor_rating = $this->skorRating($tutor);

            // ── Hitung skor total weighted ───────────────────────────────────
            // Jika online: bobot lokasi dialihkan ke rating agar tidak sia-sia
            if (!$isOffline) {
                $skor_total =
                    ($skor_mapel  * self::BOBOT_MAPEL)  +
                    ($skor_harga  * self::BOBOT_HARGA)  +
                    ($skor_jadwal * self::BOBOT_JADWAL) +
                    ($skor_rating * (self::BOBOT_RATING + self::BOBOT_LOKASI));
            } else {
                $skor_total =
                    ($skor_mapel  * self::BOBOT_MAPEL)  +
                    ($skor_lokasi * self::BOBOT_LOKASI) +
                    ($skor_harga  * self::BOBOT_HARGA)  +
                    ($skor_jadwal * self::BOBOT_JADWAL) +
                    ($skor_rating * self::BOBOT_RATING);
            }

            // ── Simpan ke matching_logs ──────────────────────────────────────
            MatchingLog::create([
                'Member_id'       => $Member_id,
                'tutor_id'       => $tutor->user_id,
                'skor_lokasi'    => round($skor_lokasi, 2),
                'skor_mapel'     => round($skor_mapel, 2),
                'skor_harga'     => round($skor_harga, 2),
                'skor_jadwal'    => round($skor_jadwal, 2),
                'skor_rating'    => round($skor_rating, 2),
                'skor_total'     => round($skor_total, 2),
                'kriteria_input' => $kriteria,
            ]);

            $hasil[] = [
                'tutor'       => $tutor,
                'skor_total'  => round($skor_total, 2),
                'skor_mapel'  => round($skor_mapel, 2),
                'skor_lokasi' => round($skor_lokasi, 2),
                'skor_harga'  => round($skor_harga, 2),
                'skor_jadwal' => round($skor_jadwal, 2),
                'skor_rating' => round($skor_rating, 2),
                'jarak_km'    => $isOffline
                    ? $this->jarakKeAreaTutor($tutor, $kriteria)
                    : null,
            ];
        }

        // Urutkan dari skor tertinggi
        usort($hasil, fn($a, $b) => $b['skor_total'] <=> $a['skor_total']);

        // Kembalikan maksimal 10 tutor terbaik
        return array_slice($hasil, 0, 10);
    }


    // ════════════════════════════════════════════════════════════════
    //  HARD FILTER HELPERS
    // ════════════════════════════════════════════════════════════════

    /**
     * FIX #2 & #3 (audit): Cek apakah tutor mendukung metode yang diminta.
     *
     * Kolom yang dipakai: tutor_profiles.metode
     * Nilai yang diharapkan: 'online' | 'offline' | 'keduanya'
     *
     * Jika member tidak pilih metode (null) → semua tutor lolos.
     */
    private function tutorSupportMetode(TutorProfile $tutor, ?string $metode): bool
    {
        if (empty($metode)) return true;

        $tutorMetode = $tutor->metode ?? 'keduanya';

        return match ($tutorMetode) {
            'online'   => $metode === 'online',
            'offline'  => $metode === 'offline',
            'keduanya' => true,
            default    => true,
        };
    }

    /**
     * FIX #6 (audit): Cek apakah tutor mengajar jenjang yang diminta.
     *
     * Kolom yang dipakai: tutor_profiles.jenjang (JSON array atau CSV)
     * Contoh nilai: ["SD","SMP"] atau "SD,SMP"
     *
     * Jika member tidak pilih jenjang (null) → semua tutor lolos.
     */
    private function tutorSupportJenjang(TutorProfile $tutor, ?string $jenjang): bool
    {
        if (empty($jenjang)) return true;
        if (empty($tutor->jenjang)) return false; // Tutor tanpa data jenjang → skip

        // Support kolom jenjang sebagai JSON array maupun string CSV
        $jenjangTutor = is_array($tutor->jenjang)
            ? $tutor->jenjang
            : array_map('trim', explode(',', $tutor->jenjang));

        return in_array($jenjang, $jenjangTutor, true);
    }


    // ════════════════════════════════════════════════════════════════
    //  SCORING FUNCTIONS
    // ════════════════════════════════════════════════════════════════

    /**
     * FIX #2 (audit): Skor Mata Pelajaran.
     *
     * SEBELUM: jika subject_id kosong → return 50.0 (mengacaukan ranking)
     * SESUDAH: jika subject_id kosong → return 100.0 (member tidak pilih mapel
     *          = tidak ada preferensi, semua tutor setara di dimensi ini)
     */
    private function skorMapel(TutorProfile $tutor, array $kriteria): float
    {
        // Member tidak pilih mapel → semua tutor setara, skor penuh
        if (empty($kriteria['subject_id'])) return 100.0;

        $punya = $tutor->subjects->pluck('id')->contains($kriteria['subject_id']);
        return $punya ? 100.0 : 0.0;
    }

    /**
     * FIX #3 & #4 (audit): Skor Lokasi — hanya aktif untuk mode offline.
     *
     * SEBELUM: berjalan untuk semua tutor; koordinat kosong → return 50.0
     * SESUDAH:
     *   - Jika mode online → return 0.0 (tidak dipakai dalam kalkulasi)
     *   - Jika mode offline:
     *       1. Coba cocokkan via TutorArea (kecamatan/kota) terlebih dahulu
     *       2. Fallback ke Haversine koordinat jika TutorArea kosong
     *       3. Koordinat tutor kosong → return 0.0 (hard filter akan skip tutor ini)
     */
    private function skorLokasi(TutorProfile $tutor, array $kriteria, bool $isOffline): float
    {
        // Mode online: dimensi lokasi tidak relevan, kembalikan 0
        if (!$isOffline) return 0.0;

        // ── Pendekatan 1: Cocokkan via TutorArea (kecamatan/kota) ──────────
        // Ini sesuai rancangan yang menyebut TutorArea sebagai basis pencocokan
        if ($tutor->areas->isNotEmpty()) {
            $kecamatanMember = $kriteria['kecamatan'] ?? null;
            $kotaMember      = $kriteria['kota'] ?? null;

            foreach ($tutor->areas as $area) {
                // Kecamatan sama → skor tertinggi
                if ($kecamatanMember && $area->kecamatan === $kecamatanMember) {
                    return 100.0;
                }
                // Kota sama → skor menengah
                if ($kotaMember && $area->kota === $kotaMember) {
                    return 70.0;
                }
            }
            // Ada data area tapi tidak ada yang cocok
            return 0.0;
        }

        // ── Pendekatan 2: Fallback ke Haversine jika TutorArea kosong ──────
        // Jika tutor tidak punya TutorArea sama sekali, gunakan koordinat
        if (
            empty($kriteria['latitude'])  || empty($kriteria['longitude']) ||
            empty($tutor->user->latitude) || empty($tutor->user->longitude)
        ) {
            // Koordinat tidak tersedia → tidak relevan untuk offline, skip
            return 0.0;
        }

        $jarak = $this->hitungJarak(
            $kriteria['latitude'],
            $kriteria['longitude'],
            $tutor->user->latitude,
            $tutor->user->longitude
        );

        if ($jarak <= 0) return 100.0;
        if ($jarak >= self::JARAK_MAX_KM) return 0.0;

        return round((1 - $jarak / self::JARAK_MAX_KM) * 100, 2);
    }

    /**
     * Skor Harga — tidak ada perubahan, logika sudah benar.
     */
    private function skorHarga(TutorProfile $tutor, array $kriteria): float
    {
        if (empty($kriteria['budget'])) return 50.0;

        $budget = (int) $kriteria['budget'];

        if ($budget >= $tutor->harga_min && $budget <= $tutor->harga_max) {
            return 100.0;
        } elseif ($budget >= $tutor->harga_min) {
            return 80.0;
        } elseif ($budget >= $tutor->harga_min * 0.8) {
            return 40.0;
        }

        return 0.0;
    }

    /**
     * FIX #5 (audit): Skor Jadwal — tambah pencocokan sesi (Pagi/Siang/Sore).
     *
     * SEBELUM: hanya cocokkan hari + jam_mulai/jam_selesai
     * SESUDAH:
     *   - Jika kriteria berisi 'sesi' → cocokkan berdasarkan sesi
     *   - Jika kriteria berisi 'jam'  → cocokkan berdasarkan jam (existing)
     *   - Keduanya bisa dipakai bersamaan; sesi lebih fleksibel untuk UX
     *
     * Catatan: pastikan controller juga mengirim 'jam' (lihat MatchController)
     */
    private function skorJadwal(TutorProfile $tutor, array $kriteria): float
    {
        if (empty($kriteria['hari'])) return 50.0;

        $jadwalCocok = $tutor->schedules
            ->where('hari', $kriteria['hari'])
            ->where('is_available', true)
            ->first();

        if (!$jadwalCocok) return 0.0;

        // ── Pencocokan Sesi (Pagi / Siang / Sore) ──────────────────────────
        if (!empty($kriteria['sesi'])) {
            $sesiRange = $this->rentangSesi($kriteria['sesi']);

            if ($sesiRange) {
                $mulaiJadwal   = $jadwalCocok->jam_mulai;   // e.g. "07:00"
                $selesaiJadwal = $jadwalCocok->jam_selesai; // e.g. "09:00"

                // Ada overlap antara jadwal tutor dan rentang sesi yang diminta
                $adaOverlap = $mulaiJadwal <= $sesiRange['selesai']
                           && $selesaiJadwal >= $sesiRange['mulai'];

                if ($adaOverlap) {
                    // Bonus jika jam juga cocok persis
                    if (!empty($kriteria['jam'])) {
                        $jamMinta   = $kriteria['jam'];
                        $jamMulai   = $jadwalCocok->jam_mulai;
                        $jamSelesai = $jadwalCocok->jam_selesai;

                        if ($jamMinta >= $jamMulai && $jamMinta <= $jamSelesai) {
                            return 100.0; // Hari + sesi + jam cocok
                        }
                        return 85.0; // Hari + sesi cocok, jam kurang pas
                    }
                    return 90.0; // Hari + sesi cocok
                }

                return 30.0; // Hari cocok tapi sesi tidak pas
            }
        }

        // ── Pencocokan Jam (jika tidak pakai sesi) ─────────────────────────
        if (!empty($kriteria['jam'])) {
            $jamMinta   = $kriteria['jam'];
            $jamMulai   = $jadwalCocok->jam_mulai;
            $jamSelesai = $jadwalCocok->jam_selesai;

            if ($jamMinta >= $jamMulai && $jamMinta <= $jamSelesai) {
                return 100.0; // Hari dan jam cocok
            }
            return 60.0; // Hari cocok tapi jam kurang pas
        }

        return 100.0; // Hanya hari yang dicek, cocok
    }

    /**
     * Skor Rating — tidak ada perubahan, logika sudah benar.
     */
    private function skorRating(TutorProfile $tutor): float
    {
        if ($tutor->total_review == 0) return 50.0;

        return round(($tutor->rating_rata / 5) * 100, 2);
    }


    // ════════════════════════════════════════════════════════════════
    //  UTILITY HELPERS
    // ════════════════════════════════════════════════════════════════

    /**
     * Kembalikan rentang jam untuk nama sesi.
     * Dipakai oleh skorJadwal().
     */
    private function rentangSesi(string $sesi): ?array
    {
        return match (strtolower($sesi)) {
            'pagi'  => self::SESI_PAGI,
            'siang' => self::SESI_SIANG,
            'sore'  => self::SESI_SORE,
            default => null,
        };
    }

    /**
     * Hitung jarak terdekat antara koordinat member ke semua TutorArea tutor.
     * Dipakai untuk mengisi kolom 'jarak_km' di hasil (bukan untuk scoring).
     */
    private function jarakKeAreaTutor(TutorProfile $tutor, array $kriteria): float
    {
        if (empty($kriteria['latitude']) || empty($kriteria['longitude'])) return 999;

        // Jika ada koordinat di TutorArea, pakai yang paling dekat
        if ($tutor->areas->isNotEmpty()) {
            $jarakMin = 999;
            foreach ($tutor->areas as $area) {
                if (!empty($area->latitude) && !empty($area->longitude)) {
                    $j = $this->hitungJarak(
                        $kriteria['latitude'], $kriteria['longitude'],
                        $area->latitude, $area->longitude
                    );
                    if ($j < $jarakMin) $jarakMin = $j;
                }
            }
            if ($jarakMin < 999) return $jarakMin;
        }

        // Fallback ke koordinat user tutor
        if (!empty($tutor->user->latitude) && !empty($tutor->user->longitude)) {
            return $this->hitungJarak(
                $kriteria['latitude'], $kriteria['longitude'],
                $tutor->user->latitude, $tutor->user->longitude
            );
        }

        return 999;
    }

    /**
     * Haversine Formula — tidak ada perubahan, logika sudah benar.
     */
    public function hitungJarak(
        float $lat1, float $lon1,
        float $lat2, float $lon2
    ): float {
        if ($lat1 == 0 || $lon1 == 0 || $lat2 == 0 || $lon2 == 0) return 999;

        $R    = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($R * $c, 2);
    }
}
